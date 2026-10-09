<?php

namespace App\Http\Controllers;

use App\Models\QrTemplate;
use App\Models\QrUnit;
use App\Models\QrClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QrTemplateController extends Controller
{
    public function index(): View
    {
        $templates = QrTemplate::query()
            ->withCount([
                'units as total_units',
                'units as empty_units' => fn ($query) => $query->where('status', QrUnit::STATUS_EMPTY),
                'units as pending_units' => fn ($query) => $query->where('status', QrUnit::STATUS_PENDING),
                'units as active_units' => fn ($query) => $query->where('status', QrUnit::STATUS_ACTIVE),
                'units as disabled_units' => fn ($query) => $query->where('status', QrUnit::STATUS_DISABLED),
            ])
            ->latest()
            ->paginate(15);

        return view('qr.templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('qr.templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', 'in:google_review'],
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
        ]);
        $data['created_by'] = $request->user()->id;

        $batch = (string) Str::uuid();
        DB::transaction(function () use ($data, $batch): void {
            $template = QrTemplate::create($data + ['shared_mode' => true]);
            QrUnit::create([
                'qr_template_id' => $template->id,
                'unit_code' => 'UNIT-000001',
                'token' => bin2hex(random_bytes(24)),
                'status' => QrUnit::STATUS_EMPTY,
                'production_batch' => $batch,
            ]);
        });

        return redirect()->route('admin.qr-units.batch.print', $batch)
            ->with('success', 'QR Template bersama berhasil dibuat. Semua lokasi akan menggunakan URL QR yang sama.');
    }

    public function show(QrTemplate $qrTemplate): View
    {
        $unitStats = $qrTemplate->units()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $units = $qrTemplate->units()->with(['currentDestination', 'destinations' => fn ($query) => $query->latest()])->latest()->paginate(30);
        $clients = QrClient::query()->where('status', 'ACTIVE')
            ->whereHas('subscriptions', fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now()))
            ->with('user')->orderBy('id')->get();

        return view('qr.templates.show', compact('qrTemplate', 'unitStats', 'units', 'clients'));
    }

    public function edit(QrTemplate $qrTemplate): View
    {
        return view('qr.templates.edit', compact('qrTemplate'));
    }

    public function update(Request $request, QrTemplate $qrTemplate): RedirectResponse
    {
        $qrTemplate->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', 'in:google_review,qr_nfc'],
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
        ]));

        return redirect()->route('admin.qr-templates.show', $qrTemplate)
            ->with('success', 'Template QR berhasil diperbarui.');
    }

    public function destroy(QrTemplate $qrTemplate): RedirectResponse
    {
        DB::transaction(function () use ($qrTemplate): void {
            $lockedTemplate = QrTemplate::query()->lockForUpdate()->findOrFail($qrTemplate->id);
            $lockedTemplate->units()->delete();
            $lockedTemplate->delete();
        });

        return redirect()->route('admin.qr-templates.index')
            ->with('success', 'Template, seluruh QR unit, destination, dan riwayat scan terkait berhasil dihapus.');
    }

    public function produce(Request $request, QrTemplate $qrTemplate): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:5000'],
            'qr_client_id' => ['nullable', 'integer', 'exists:qr_clients,id'],
        ]);

        if ($qrTemplate->status !== 'ACTIVE') {
            return back()->withErrors(['quantity' => 'Template tidak aktif dan tidak dapat diproduksi.']);
        }

        if ($qrTemplate->shared_mode) {
            return back()->withErrors(['quantity' => 'QR Template ini memakai satu URL bersama. Tambahkan lokasi melalui QR publik, bukan produksi unit baru.']);
        }

        if (!empty($data['qr_client_id'])) {
            $client = QrClient::query()->where('status', 'ACTIVE')->with(['subscriptions' => fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now())])->findOrFail($data['qr_client_id']);
            $subscription = $client->subscriptions->sortByDesc('ends_at')->first();
            if (!$subscription || $client->units()->count() + (int) $data['quantity'] > $subscription->unit_limit) {
                return back()->withErrors(['qr_client_id' => 'Subscription klien tidak aktif atau batas unit paket akan terlampaui.']);
            }
        }

        $batch = (string) Str::uuid();

        DB::transaction(function () use ($qrTemplate, $data, $batch): void {
            $lockedTemplate = QrTemplate::query()->lockForUpdate()->findOrFail($qrTemplate->id);
            $lastCode = $lockedTemplate->units()->orderByDesc('id')->value('unit_code');
            $nextNumber = $lastCode ? ((int) substr($lastCode, strlen('UNIT-')) + 1) : 1;

            for ($offset = 0; $offset < (int) $data['quantity']; $offset++) {
                QrUnit::create([
                    'qr_template_id' => $lockedTemplate->id,
                    'qr_client_id' => $data['qr_client_id'] ?? null,
                    'unit_code' => 'UNIT-' . str_pad((string) ($nextNumber + $offset), 6, '0', STR_PAD_LEFT),
                    'token' => bin2hex(random_bytes(24)),
                    'activation_code' => strtoupper(Str::random(10)),
                    'status' => QrUnit::STATUS_EMPTY,
                    'production_batch' => $batch,
                ]);
            }
        });

        return redirect()->route('admin.qr-units.batch.print', $batch)
            ->with('success', $data['quantity'] . ' unit QR berhasil diproduksi.');
    }
}
