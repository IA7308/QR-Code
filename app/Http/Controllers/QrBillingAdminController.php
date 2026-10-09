<?php

namespace App\Http\Controllers;

use App\Models\QrClient;
use App\Models\QrInvoice;
use App\Models\QrPlan;
use App\Models\QrSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrBillingAdminController extends Controller
{
    public function clients(): View
    {
        $clients = QrClient::with(['user', 'subscriptions.plan'])->withCount('units')->latest()->paginate(30);

        return view('qr.admin.clients', compact('clients'));
    }

    public function updateClientStatus(Request $request, QrClient $qrClient): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:ACTIVE,SUSPENDED']]);
        $qrClient->update(['status' => $data['status']]);

        return back()->with('success', 'Status akun klien diperbarui.');
    }

    public function plans(): View
    {
        $plans = QrPlan::withCount('subscriptions')->orderBy('price')->get();

        return view('qr.admin.plans', compact('plans'));
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'unit_limit' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);
        $data['status'] = 'ACTIVE';
        QrPlan::create($data);

        return back()->with('success', 'Paket subscription berhasil dibuat.');
    }

    public function updatePlan(Request $request, QrPlan $qrPlan): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'unit_limit' => ['required', 'integer', 'min:1', 'max:100000'],
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
        ]);
        $qrPlan->update($data);

        return back()->with('success', 'Paket subscription diperbarui. Invoice yang sudah dibuat tetap memakai harga saat invoice dibuat.');
    }

    public function invoices(): View
    {
        $invoices = QrInvoice::with(['subscription.client.user', 'subscription.plan'])->latest()->paginate(30);

        return view('qr.admin.invoices', compact('invoices'));
    }

    public function proofFile(QrInvoice $qrInvoice): StreamedResponse
    {
        abort_unless($qrInvoice->proof_path && Storage::disk('local')->exists($qrInvoice->proof_path), 404);

        return Storage::disk('local')->download($qrInvoice->proof_path);
    }

    public function verify(QrInvoice $qrInvoice): RedirectResponse
    {
        $data = request()->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);
        abort_unless($qrInvoice->status === 'PENDING' && $qrInvoice->proof_path, 409, 'Invoice belum memiliki bukti pembayaran yang menunggu verifikasi.');

        DB::transaction(function () use ($qrInvoice, $data): void {
            $invoice = QrInvoice::query()->lockForUpdate()->findOrFail($qrInvoice->id);
            abort_unless($invoice->status === 'PENDING' && $invoice->proof_path, 409);
            $subscription = QrSubscription::query()->with('plan')->lockForUpdate()->findOrFail($invoice->qr_subscription_id);
            $startsAt = now();
            $endsAt = $subscription->billing_cycle === 'yearly' ? $startsAt->copy()->addYear() : $startsAt->copy()->addMonth();
            $subscription->update(['status' => 'ACTIVE', 'starts_at' => $startsAt, 'ends_at' => $endsAt]);
            $invoice->update(['status' => 'PAID', 'paid_at' => now(), 'admin_note' => $data['admin_note'] ?? null]);
        });

        return back()->with('success', 'Pembayaran diverifikasi. Subscription klien sekarang aktif.');
    }

    public function reject(Request $request, QrInvoice $qrInvoice): RedirectResponse
    {
        $data = $request->validate(['admin_note' => ['required', 'string', 'max:1000']]);
        abort_unless($qrInvoice->status === 'PENDING', 409, 'Invoice ini tidak menunggu verifikasi.');
        $qrInvoice->update(['status' => 'REJECTED', 'admin_note' => $data['admin_note']]);

        return back()->with('success', 'Bukti pembayaran ditolak. Klien dapat mengirim ulang bukti yang benar.');
    }
}
