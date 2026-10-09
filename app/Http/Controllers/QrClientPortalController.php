<?php

namespace App\Http\Controllers;

use App\Models\QrClient;
use App\Models\QrInvoice;
use App\Models\QrPlan;
use App\Models\QrScanEvent;
use App\Models\QrSubscription;
use App\Models\QrUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrClientPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $client = $this->client($request);
        $subscription = $client->subscriptions()->with('plan')->latest()->first();
        $subscriptionDisplayStatus = $subscription?->status;
        if ($subscriptionDisplayStatus === 'ACTIVE' && $subscription->ends_at?->isPast()) {
            $subscriptionDisplayStatus = 'EXPIRED';
        }
        $invoices = QrInvoice::query()->whereHas('subscription', fn ($query) => $query->where('qr_client_id', $client->id))
            ->latest()->limit(5)->get();
        $unitCount = $client->units()->count();
        $scanCount = QrScanEvent::where('event_type', QrScanEvent::TYPE_SCAN)
            ->whereHas('unit', fn ($query) => $query->where('qr_client_id', $client->id))->count();
        $reviewClickCount = QrScanEvent::where('event_type', QrScanEvent::TYPE_REVIEW_CLICK)
            ->whereHas('unit', fn ($query) => $query->where('qr_client_id', $client->id))->count();

        return view('qr.client.dashboard', compact('client', 'subscription', 'subscriptionDisplayStatus', 'invoices', 'unitCount', 'scanCount', 'reviewClickCount'));
    }

    public function plans(Request $request): View
    {
        $client = $this->client($request);
        $plans = QrPlan::where('status', 'ACTIVE')->orderBy('price')->get();
        $hasOpenSubscription = $client->subscriptions()->whereIn('status', ['PENDING', 'ACTIVE'])
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->exists();

        return view('qr.client.plans', compact('plans', 'hasOpenSubscription'));
    }

    public function subscribe(Request $request, QrPlan $qrPlan): RedirectResponse
    {
        $client = $this->client($request);
        abort_unless($qrPlan->status === 'ACTIVE', 404);
        if ($client->status !== 'ACTIVE') {
            return back()->with('error', 'Akun klien sedang dinonaktifkan. Hubungi admin.');
        }
        if ($client->subscriptions()->whereIn('status', ['PENDING', 'ACTIVE'])
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))->exists()) {
            return back()->with('error', 'Selesaikan tagihan atau subscription aktif terlebih dahulu.');
        }

        $subscription = $client->subscriptions()->create([
            'qr_plan_id' => $qrPlan->id,
            'billing_cycle' => $qrPlan->billing_cycle,
            'unit_limit' => $qrPlan->unit_limit,
            'status' => 'PENDING',
        ]);
        $invoice = $subscription->invoices()->create([
            'invoice_number' => 'QR-' . now()->format('ymd') . '-' . Str::upper(Str::random(6)),
            'amount' => $qrPlan->price,
            'status' => 'PENDING',
            'due_at' => now()->addDays(3),
        ]);

        return redirect()->route('client.invoices.show', $invoice)->with('success', 'Invoice subscription berhasil dibuat.');
    }

    public function cancelSubscription(Request $request, QrSubscription $qrSubscription): RedirectResponse
    {
        $client = $this->client($request);
        abort_unless($qrSubscription->qr_client_id === $client->id, 404);
        abort_unless($qrSubscription->status === 'PENDING', 409, 'Hanya subscription yang belum dibayar yang dapat dibatalkan.');

        foreach ($qrSubscription->invoices()->whereIn('status', ['PENDING', 'REJECTED'])->get() as $invoice) {
            if ($invoice->proof_path) {
                Storage::disk('local')->delete($invoice->proof_path);
            }
        }
        $qrSubscription->update(['status' => 'CANCELLED']);
        $qrSubscription->invoices()->whereIn('status', ['PENDING', 'REJECTED'])->update(['status' => 'CANCELLED', 'proof_path' => null]);

        return redirect()->route('client.plans')->with('success', 'Pengajuan subscription dibatalkan. Anda dapat memilih paket lain.');
    }

    public function invoice(Request $request, QrInvoice $qrInvoice): View
    {
        $client = $this->client($request);
        abort_unless($qrInvoice->subscription()->where('qr_client_id', $client->id)->exists(), 404);
        $qrInvoice->load('subscription.plan');

        return view('qr.client.invoice', compact('qrInvoice'));
    }

    public function submitProof(Request $request, QrInvoice $qrInvoice): RedirectResponse
    {
        $client = $this->client($request);
        abort_unless($qrInvoice->subscription()->where('qr_client_id', $client->id)->exists(), 404);
        abort_unless(in_array($qrInvoice->status, ['PENDING', 'REJECTED'], true), 409, 'Invoice ini tidak menerima bukti pembayaran.');
        $data = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'client_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($qrInvoice->proof_path) {
            Storage::disk('local')->delete($qrInvoice->proof_path);
        }
        $qrInvoice->update([
            'proof_path' => $data['proof']->store('qr-payment-proofs', 'local'),
            'client_note' => $data['client_note'] ?? null,
            'admin_note' => null,
            'status' => 'PENDING',
        ]);

        return redirect()->route('client.invoices.show', $qrInvoice)->with('success', 'Bukti pembayaran terkirim dan menunggu verifikasi admin.');
    }

    public function proofFile(Request $request, QrInvoice $qrInvoice): StreamedResponse
    {
        $client = $this->client($request);
        abort_unless($qrInvoice->subscription()->where('qr_client_id', $client->id)->exists(), 404);
        abort_unless($qrInvoice->proof_path && Storage::disk('local')->exists($qrInvoice->proof_path), 404);

        return Storage::disk('local')->download($qrInvoice->proof_path);
    }

    public function units(Request $request): View
    {
        $client = $this->client($request);
        $units = $client->units()->with(['template', 'currentDestination'])->withCount([
            'scanEvents as scans_count' => fn ($query) => $query->where('event_type', QrScanEvent::TYPE_SCAN),
            'scanEvents as review_clicks_count' => fn ($query) => $query->where('event_type', QrScanEvent::TYPE_REVIEW_CLICK),
        ])->latest()->paginate(24);

        return view('qr.client.units', compact('units'));
    }

    public function printUnit(Request $request, QrUnit $qrUnit): View
    {
        $client = $this->client($request);
        abort_unless($qrUnit->qr_client_id === $client->id, 404);
        $units = collect([$qrUnit->load('template')]);
        $batch = null;

        return view('qr.units.print', compact('units', 'batch'));
    }

    private function client(Request $request): QrClient
    {
        $client = QrClient::where('user_id', $request->user()->id)->firstOrFail();
        abort_unless($client->status === 'ACTIVE', 403, 'Akun klien sedang dinonaktifkan.');

        return $client;
    }
}
