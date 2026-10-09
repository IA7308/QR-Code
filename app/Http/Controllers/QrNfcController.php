<?php

namespace App\Http\Controllers;

use App\Models\QrClient;
use App\Models\QrTemplate;
use App\Models\QrUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QrNfcController extends Controller
{
    public function index(): View
    {
        $templates = QrTemplate::query()->where('type', 'qr_nfc')
            ->withCount('units')->latest()->paginate(15);
        $clients = QrClient::query()->where('status', 'ACTIVE')
            ->whereHas('subscriptions', fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now()))
            ->with(['user', 'subscriptions' => fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now())])
            ->orderBy('company_name')->get();

        return view('qr.nfc.index', compact('templates', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'quantity' => ['required', 'integer', 'min:1', 'max:5000'],
            'qr_client_id' => ['nullable', 'integer', 'exists:qr_clients,id'],
        ]);

        if (!empty($data['qr_client_id'])) {
            $client = QrClient::query()->where('status', 'ACTIVE')
                ->whereHas('subscriptions', fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now()))
                ->with(['subscriptions' => fn ($query) => $query->where('status', 'ACTIVE')->where('ends_at', '>', now())])
                ->findOrFail($data['qr_client_id']);
            $subscription = $client->subscriptions->sortByDesc('ends_at')->first();
            if (!$subscription || $client->units()->count() + (int) $data['quantity'] > $subscription->unit_limit) {
                return back()->withInput()->withErrors(['qr_client_id' => 'Subscription klien tidak aktif atau batas unit paket akan terlampaui.']);
            }
        }

        $batch = (string) Str::uuid();
        DB::transaction(function () use ($data, $batch, $request): void {
            $template = QrTemplate::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'type' => 'qr_nfc',
                'status' => 'ACTIVE',
                'created_by' => $request->user()->id,
                'shared_mode' => false,
            ]);

            for ($number = 1; $number <= (int) $data['quantity']; $number++) {
                QrUnit::create([
                    'qr_template_id' => $template->id,
                    'qr_client_id' => $data['qr_client_id'] ?? null,
                    'unit_code' => 'NFC-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT),
                    'token' => bin2hex(random_bytes(24)),
                    'status' => QrUnit::STATUS_EMPTY,
                    'production_batch' => $batch,
                ]);
            }
        });

        return redirect()->route('admin.qr-units.batch.print', $batch)
            ->with('success', 'Batch QR + NFC berhasil dibuat. Setiap URL pada lembar ini dipakai untuk QR dan ditulis ke NFC unit yang sama.');
    }

    public function destroy(QrTemplate $qrTemplate): RedirectResponse
    {
        abort_unless($qrTemplate->type === 'qr_nfc', 404);

        DB::transaction(function () use ($qrTemplate): void {
            $template = QrTemplate::query()->lockForUpdate()->findOrFail($qrTemplate->id);
            $template->units()->delete();
            $template->delete();
        });

        return redirect()->route('admin.qr-nfc.index')
            ->with('success', 'Batch QR + NFC beserta seluruh unit, tujuan, dan riwayat terkait berhasil dihapus.');
    }
}
