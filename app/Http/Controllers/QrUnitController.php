<?php

namespace App\Http\Controllers;

use App\Models\QrDestination;
use App\Models\QrScanEvent;
use App\Models\QrTemplate;
use App\Models\QrUnit;
use App\Services\GooglePlaceLookup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrUnitController extends Controller
{
    public function autocomplete(Request $request, GooglePlaceLookup $places): JsonResponse
    {
        $data = $request->validate([
            'query' => ['required', 'string', 'min:3', 'max:200'],
            'session_token' => ['required', 'string', 'max:36'],
        ]);

        try {
            return response()->json(['suggestions' => $places->autocomplete($data['query'], $data['session_token'])]);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    public function publicShow(string $token): View|RedirectResponse
    {
        $unit = QrUnit::query()
            ->with(['template', 'currentDestination'])
            ->where('token', $token)
            ->firstOrFail();

        QrScanEvent::create(['qr_unit_id' => $unit->id, 'event_type' => QrScanEvent::TYPE_SCAN]);

        if ($unit->qr_client_id && !$unit->client()->where('status', 'ACTIVE')->whereHas('subscriptions', fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now()))->exists()) {
            return response()->view('qr.expired', compact('unit'), 403);
        }

        if ($unit->template->shared_mode) {
            if ($unit->status === QrUnit::STATUS_DISABLED) {
                return view('qr.public', compact('unit'));
            }
            $locations = $unit->destinations()->whereNull('deactivated_at')->orderBy('place_name')->get();
            return view('qr.shared', compact('unit', 'locations'));
        }

        if ($unit->status === QrUnit::STATUS_ACTIVE && $unit->currentDestination) {
            QrScanEvent::create(['qr_unit_id' => $unit->id, 'event_type' => QrScanEvent::TYPE_REVIEW_CLICK]);
            return redirect()->away($unit->currentDestination->review_url);
        }

        $activationUrl = request()->routeIs('qr.nfc.show')
            ? route('qr.nfc.activate', $unit->token)
            : route('qr.public.activate', $unit->token);

        return view('qr.public', compact('unit', 'activationUrl'));
    }

    public function activatePublic(Request $request, string $token, GooglePlaceLookup $places): RedirectResponse
    {
        $unit = QrUnit::query()->where('token', $token)->firstOrFail();
        if ($unit->template->shared_mode) {
            return $this->activateSharedLocation($request, $unit, $places);
        }
        $data = $request->validate([
            'place_name' => ['required', 'string', 'max:255'],
            'place_address' => ['required', 'string', 'max:2048'],
            'place_id' => ['required', 'string', 'max:255'],
            'session_token' => ['required', 'string', 'max:36'],
        ]);

        $attempt = DB::transaction(function () use ($unit): array {
            $lockedUnit = QrUnit::query()->lockForUpdate()->findOrFail($unit->id);
            if ($lockedUnit->status !== QrUnit::STATUS_EMPTY) {
                return ['status' => $lockedUnit->status, 'acquired' => false];
            }

            $lockedUnit->update(['status' => QrUnit::STATUS_PENDING]);

            return ['status' => QrUnit::STATUS_PENDING, 'acquired' => true];
        });

        if (!$attempt['acquired']) {
            $unit->refresh()->load('currentDestination');
            if ($unit->status === QrUnit::STATUS_ACTIVE && $unit->currentDestination) {
                QrScanEvent::create(['qr_unit_id' => $unit->id, 'event_type' => QrScanEvent::TYPE_REVIEW_CLICK]);
                return redirect()->away($unit->currentDestination->review_url);
            }

            return redirect()->route('qr.public.show', $token)
                ->withErrors(['place_name' => $attempt['status'] === QrUnit::STATUS_DISABLED
                    ? 'QR ini tidak aktif. Hubungi penjual.'
                    : 'QR sedang diproses. Coba pindai kembali sebentar lagi.']);
        }

        try {
            $destination = $places->details($data['place_id'], $data['session_token']);
        } catch (\Throwable $exception) {
            report($exception);
            DB::transaction(function () use ($unit): void {
                $lockedUnit = QrUnit::query()->lockForUpdate()->find($unit->id);
                if ($lockedUnit?->status === QrUnit::STATUS_PENDING) {
                    $lockedUnit->update(['status' => QrUnit::STATUS_EMPTY]);
                }
            });

            return redirect()->route('qr.public.show', $token)
                ->withInput()
                ->withErrors(['place_address' => $exception->getMessage()]);
        }

        $activation = DB::transaction(function () use ($unit, $data, $destination): array {
            $lockedUnit = QrUnit::query()->lockForUpdate()->findOrFail($unit->id);
            if ($lockedUnit->status !== QrUnit::STATUS_PENDING) {
                return ['status' => $lockedUnit->status, 'review_url' => null];
            }

            $lockedUnit->destinations()
                ->whereNull('deactivated_at')
                ->update(['deactivated_at' => now(), 'updated_at' => now()]);

            $savedDestination = $lockedUnit->destinations()->create([
                'place_name' => $data['place_name'],
                'place_address' => mb_substr($destination['place_address'], 0, 255),
                'place_id' => $destination['place_id'],
                'maps_url' => $destination['maps_url'],
                'review_url' => $destination['review_url'],
                'activated_at' => now(),
            ]);
            $lockedUnit->update(['status' => QrUnit::STATUS_ACTIVE]);
            $lockedUnit->update(['activation_code' => null]);

            return ['status' => QrUnit::STATUS_ACTIVE, 'review_url' => $savedDestination->review_url];
        });

        if ($activation['status'] === QrUnit::STATUS_ACTIVE && $activation['review_url']) {
            QrScanEvent::create(['qr_unit_id' => $unit->id, 'event_type' => QrScanEvent::TYPE_REVIEW_CLICK]);
            return redirect()->away($activation['review_url']);
        }

        return redirect()->route('qr.public.show', $token)
            ->withErrors(['place_name' => 'QR berubah status saat diproses. Pindai kembali untuk melanjutkan.']);
    }

    private function activateSharedLocation(Request $request, QrUnit $unit, GooglePlaceLookup $places): RedirectResponse
    {
        if ($unit->status === QrUnit::STATUS_DISABLED) {
            return back()->withErrors(['place_name' => 'QR ini tidak aktif. Hubungi pihak yang memberikan QR.']);
        }

        $data = $request->validate([
            'place_name' => ['required', 'string', 'max:255'],
            'place_address' => ['required', 'string', 'max:2048'],
            'place_id' => ['required', 'string', 'max:255'],
            'session_token' => ['required', 'string', 'max:36'],
        ]);

        try {
            $place = $places->details($data['place_id'], $data['session_token']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->withErrors(['place_address' => $exception->getMessage()]);
        }

        $alreadyAdded = $unit->destinations()
            ->whereNull('deactivated_at')
            ->where('place_id', $place['place_id'])
            ->exists();
        if ($alreadyAdded) {
            return redirect()->route('qr.public.show', $unit->token)
                ->with('success', 'Lokasi tersebut sudah terdaftar pada QR ini.');
        }

        $activationCode = strtoupper(Str::random(10));
        $unit->destinations()->create([
            'place_name' => $data['place_name'],
            'place_address' => mb_substr($place['place_address'], 0, 255),
            'place_id' => $place['place_id'],
            'activation_code' => $activationCode,
            'maps_url' => $place['maps_url'],
            'review_url' => $place['review_url'],
            'activated_at' => now(),
        ]);
        $unit->update(['status' => QrUnit::STATUS_ACTIVE]);

        return redirect()->route('qr.public.show', $unit->token)
            ->with('success', 'Lokasi berhasil ditambahkan. Kode aktivasi dibuat otomatis.');
    }

    public function selectSharedDestination(Request $request, string $token): RedirectResponse
    {
        $data = $request->validate(['activation_code' => ['required', 'string', 'size:10']]);
        $unit = QrUnit::query()->where('token', $token)->firstOrFail();
        abort_unless($unit->template->shared_mode, 404);
        abort_if($unit->status === QrUnit::STATUS_DISABLED, 404);
        $destination = $unit->destinations()
            ->whereNull('deactivated_at')
            ->where('activation_code', strtoupper($data['activation_code']))
            ->first();

        if (!$destination) {
            return back()->withErrors(['location' => 'Lokasi yang dipilih sudah tidak tersedia.']);
        }

        QrScanEvent::create(['qr_unit_id' => $unit->id, 'event_type' => QrScanEvent::TYPE_REVIEW_CLICK]);

        return redirect()->away($destination->review_url);
    }

    public function index(Request $request): View
    {
        $query = QrUnit::query()->with(['template', 'currentDestination'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }
        if ($request->filled('template')) {
            $query->where('qr_template_id', $request->integer('template'));
        }
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search): void {
                $builder->where('unit_code', 'like', "%{$search}%")
                    ->orWhere('token', 'like', "%{$search}%")
                    ->orWhereHas('destinations', function ($destinationQuery) use ($search): void {
                        $destinationQuery->where('place_name', 'like', "%{$search}%")
                            ->orWhere('place_address', 'like', "%{$search}%");
                    });
            });
        }

        $units = $query->paginate(50)->withQueryString();
        $templates = \App\Models\QrTemplate::query()->orderBy('name')->get(['id', 'name']);

        return view('qr.units.index', compact('units', 'templates'));
    }

    public function show(QrUnit $qrUnit): View
    {
        $qrUnit->load(['template', 'client.user', 'currentDestination', 'destinations' => fn ($query) => $query->latest()]);

        return view('qr.units.show', compact('qrUnit'));
    }

    public function printBatch(string $batch): View
    {
        abort_unless((bool) preg_match('/^[0-9a-f-]{36}$/i', $batch), 404);
        $units = QrUnit::query()
            ->with('template')
            ->where('production_batch', $batch)
            ->orderBy('id')
            ->get();
        abort_if($units->isEmpty(), 404);

        return view('qr.units.print', compact('units', 'batch'));
    }

    public function printTemplate(QrTemplate $qrTemplate): View
    {
        $units = QrUnit::query()
            ->with('template')
            ->where('qr_template_id', $qrTemplate->id)
            ->orderBy('id')
            ->get();
        abort_if($units->isEmpty(), 404, 'Template ini belum memiliki QR unit.');

        $batch = null;

        return view('qr.units.print', compact('units', 'batch'));
    }

    public function exportBatch(string $batch): StreamedResponse
    {
        abort_unless((bool) preg_match('/^[0-9a-f-]{36}$/i', $batch), 404);
        $units = QrUnit::query()->with('template')->where('production_batch', $batch)->orderBy('id')->get();
        abort_if($units->isEmpty(), 404);

        return response()->streamDownload(function () use ($units): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['unit_code', 'token', 'status', 'qr_url', 'nfc_url']);
            foreach ($units as $unit) {
                $url = $unit->template->type === 'qr_nfc'
                    ? route('qr.nfc.show', $unit->token)
                    : route('qr.public.show', $unit->token);
                fputcsv($output, [$unit->unit_code, $unit->token, $unit->status, $url, $unit->template->type === 'qr_nfc' ? $url : '']);
            }
            fclose($output);
        }, 'qr-units-' . substr($batch, 0, 8) . '.csv');
    }

    public function disable(QrUnit $qrUnit): RedirectResponse
    {
        $qrUnit->update(['status' => QrUnit::STATUS_DISABLED]);

        return back()->with('success', 'Unit QR dinonaktifkan. Riwayat destination tetap tersimpan.');
    }

    public function enable(QrUnit $qrUnit): RedirectResponse
    {
        $hasDestination = $qrUnit->destinations()->whereNull('deactivated_at')->exists();
        $qrUnit->update(['status' => $hasDestination ? QrUnit::STATUS_ACTIVE : QrUnit::STATUS_EMPTY]);

        return back()->with('success', 'Unit QR diaktifkan kembali.');
    }

    public function reset(QrUnit $qrUnit): RedirectResponse
    {
        DB::transaction(function () use ($qrUnit): void {
            $lockedUnit = QrUnit::query()->lockForUpdate()->findOrFail($qrUnit->id);
            $lockedUnit->destinations()
                ->whereNull('deactivated_at')
                ->update(['deactivated_at' => now(), 'updated_at' => now()]);
            $lockedUnit->update(['status' => QrUnit::STATUS_EMPTY]);
        });

        return back()->with('success', 'Destination lama diarsipkan. QR yang sama kini siap diisi ulang.');
    }

    public function destroy(QrUnit $qrUnit): RedirectResponse
    {
        $unitCode = $qrUnit->unit_code;
        $qrUnit->delete();

        return redirect()->route('admin.qr-units.index')
            ->with('success', "QR unit {$unitCode} beserta destination dan riwayat scan-nya berhasil dihapus.");
    }
}
