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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrBillingAdminController extends Controller
{
    public function clients(): View
    {
        $clients = QrClient::with([
            'user',
            'subscriptions.plan',
            'units:id,qr_client_id,unit_code,status',
            'units.currentDestination',
        ])->withCount('units')->latest()->paginate(30);

        return view('qr.admin.clients', compact('clients'));
    }

    public function editClient(QrClient $qrClient): View
    {
        $qrClient->load('user');

        return view('qr.admin.client-edit', compact('qrClient'));
    }

    public function updateClient(Request $request, QrClient $qrClient): RedirectResponse
    {
        $user = $qrClient->user;
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
            'pin' => ['nullable', 'string', 'digits_between:4,8'],
            'clear_pin' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data, $qrClient, $user): void {
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            if (filled($data['password'] ?? null)) {
                $user->password = Hash::make($data['password']);
                $qrClient->password_encrypted = $data['password'];
            }
            $user->save();

            $qrClient->company_name = $data['company_name'] ?? null;
            if (filled($data['pin'] ?? null)) {
                $qrClient->pin_hash = Hash::make($data['pin']);
                $qrClient->pin_encrypted = $data['pin'];
            } elseif (!empty($data['clear_pin'])) {
                $qrClient->pin_hash = null;
                $qrClient->pin_encrypted = null;
            }
            $qrClient->save();
        });

        return redirect()->route('admin.qr-clients.index')->with('success', 'Data klien berhasil diperbarui.');
    }

    public function destroyClient(QrClient $qrClient): RedirectResponse
    {
        $proofPaths = QrInvoice::query()
            ->whereHas('subscription', fn ($query) => $query->where('qr_client_id', $qrClient->id))
            ->whereNotNull('proof_path')
            ->pluck('proof_path');
        DB::transaction(fn () => $qrClient->user()->firstOrFail()->delete());
        foreach ($proofPaths as $proofPath) {
            Storage::disk('local')->delete($proofPath);
        }

        return redirect()->route('admin.qr-clients.index')->with('success', 'Akun klien dihapus. Unit QR dilepas dari akun; subscription dan invoice ikut dihapus.');
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
