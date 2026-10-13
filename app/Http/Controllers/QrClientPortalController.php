<?php

namespace App\Http\Controllers;

use App\Models\QrClient;
use App\Models\GuestFeedback;
use App\Models\QrInvoice;
use App\Models\QrPlan;
use App\Models\QrScanEvent;
use App\Models\QrSubscription;
use App\Models\QrUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrClientPortalController extends Controller
{
    public function editProfile(Request $request): View
    {
        $client = QrClient::where('user_id', $request->user()->id)->firstOrFail();

        return view('qr.client.profile', compact('client'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $client = QrClient::where('user_id', $request->user()->id)->firstOrFail();
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'current_password' => ['required_with:password,pin,clear_pin', 'current_password'],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
            'pin' => ['nullable', 'string', 'digits_between:4,8'],
            'clear_pin' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data, $client, $user): void {
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            if (filled($data['password'] ?? null)) {
                $user->password = Hash::make($data['password']);
                $client->password_encrypted = $data['password'];
            }
            $user->save();

            $client->company_name = $data['company_name'] ?? null;
            if (filled($data['pin'] ?? null)) {
                $client->pin_hash = Hash::make($data['pin']);
                $client->pin_encrypted = $data['pin'];
            } elseif (!empty($data['clear_pin'])) {
                $client->pin_hash = null;
                $client->pin_encrypted = null;
            }
            $client->save();
        });

        return redirect()->route('client.profile.edit')->with('success', 'Profil klien berhasil diperbarui.');
    }

    public function destroyAccount(Request $request): RedirectResponse
    {
        $request->validate(['current_password' => ['required', 'current_password']]);
        $user = $request->user();
        $client = QrClient::where('user_id', $user->id)->firstOrFail();
        $proofPaths = QrInvoice::query()
            ->whereHas('subscription', fn ($query) => $query->where('qr_client_id', $client->id))
            ->whereNotNull('proof_path')
            ->pluck('proof_path');

        DB::transaction(fn () => $user->delete());
        foreach ($proofPaths as $proofPath) {
            Storage::disk('local')->delete($proofPath);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun klien telah dihapus.');
    }

    public function dashboard(Request $request): View
    {
        $client = $this->client($request);
        $subscription = $client->subscriptions()->with('plan')->latest()->first();
        $analyticsUnlocked = $client->subscriptions()
            ->where('status', 'ACTIVE')
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->whereHas('plan', fn ($query) => $query->whereRaw('LOWER(name) = ?', ['starter']))
            ->whereHas('invoices', fn ($query) => $query->where('status', 'PAID'))
            ->exists();
        $subscriptionDisplayStatus = $subscription?->status;
        if ($subscriptionDisplayStatus === 'ACTIVE' && $subscription->ends_at?->isPast()) {
            $subscriptionDisplayStatus = 'EXPIRED';
        }
        $invoices = QrInvoice::query()->whereHas('subscription', fn ($query) => $query->where('qr_client_id', $client->id))
            ->latest()->limit(5)->get();
        $unitIds = $client->units()->pluck('id');
        $unitCount = $unitIds->count();
        $activeUnitCount = $client->units()->where('status', QrUnit::STATUS_ACTIVE)->count();
        $scanEvents = QrScanEvent::query()->whereIn('qr_unit_id', $unitIds);
        $scanCount = (clone $scanEvents)->where('event_type', QrScanEvent::TYPE_SCAN)->count();
        $reviewClickCount = (clone $scanEvents)->where('event_type', QrScanEvent::TYPE_REVIEW_CLICK)->count();

        $startDate = today()->subDays(13);
        $dailyRows = QrScanEvent::query()
            ->whereIn('qr_unit_id', $unitIds)
            ->where('created_at', '>=', $startDate->startOfDay())
            ->selectRaw('DATE(created_at) as event_date, event_type, COUNT(*) as total')
            ->groupBy('event_date', 'event_type')
            ->get();
        $dailyMap = [];
        foreach ($dailyRows as $row) {
            $dailyMap[$row->event_date][$row->event_type] = (int) $row->total;
        }
        $dailyAnalytics = collect(range(0, 13))->map(function (int $offset) use ($startDate, $dailyMap): array {
            $date = $startDate->copy()->addDays($offset);
            $counts = $dailyMap[$date->toDateString()] ?? [];

            return [
                'date' => $date->toDateString(),
                'label' => $date->format('d/m'),
                'scans' => $counts[QrScanEvent::TYPE_SCAN] ?? 0,
                'review_clicks' => $counts[QrScanEvent::TYPE_REVIEW_CLICK] ?? 0,
            ];
        });

        $feedbackQuery = GuestFeedback::query()->whereIn('qr_unit_id', $unitIds);
        $feedbackCount = (clone $feedbackQuery)->count();
        $averageRating = (clone $feedbackQuery)->avg('rating');
        $ratingDistribution = (clone $feedbackQuery)
            ->select('rating', DB::raw('COUNT(*) as total'))
            ->groupBy('rating')
            ->get()
            ->keyBy('rating');
        $feedbackByUnit = (clone $feedbackQuery)
            ->select('qr_unit_id', DB::raw('COUNT(*) as feedback_count'), DB::raw('AVG(rating) as average_rating'))
            ->groupBy('qr_unit_id')
            ->get()
            ->keyBy('qr_unit_id');
        $units = $client->units()
            ->with('currentDestination')
            ->withCount([
                'scanEvents as scans_count' => fn ($query) => $query->where('event_type', QrScanEvent::TYPE_SCAN),
                'scanEvents as review_clicks_count' => fn ($query) => $query->where('event_type', QrScanEvent::TYPE_REVIEW_CLICK),
            ])
            ->get()
            ->sortByDesc('scans_count')
            ->values();

        return view('qr.client.dashboard', compact(
            'client', 'subscription', 'subscriptionDisplayStatus', 'analyticsUnlocked', 'invoices', 'unitCount', 'activeUnitCount',
            'scanCount', 'reviewClickCount', 'dailyAnalytics', 'feedbackCount', 'averageRating',
            'ratingDistribution', 'feedbackByUnit', 'units',
        ));
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

    public function unitDetail(Request $request, QrUnit $qrUnit): View
    {
        $client = $this->client($request);
        abort_unless($qrUnit->qr_client_id === $client->id, 404);

        $qrUnit->load(['template', 'currentDestination', 'destinations' => fn ($query) => $query->latest()]);
        $scanCount = $qrUnit->scanEvents()->where('event_type', QrScanEvent::TYPE_SCAN)->count();
        $reviewClickCount = $qrUnit->scanEvents()->where('event_type', QrScanEvent::TYPE_REVIEW_CLICK)->count();
        $feedback = $qrUnit->guestFeedback()->latest()->paginate(10);
        $averageRating = $qrUnit->guestFeedback()->avg('rating');

        return view('qr.client.unit-detail', compact('qrUnit', 'scanCount', 'reviewClickCount', 'feedback', 'averageRating'));
    }

    private function client(Request $request): QrClient
    {
        $client = QrClient::where('user_id', $request->user()->id)->firstOrFail();
        abort_unless($client->status === 'ACTIVE', 403, 'Akun klien sedang dinonaktifkan.');

        return $client;
    }
}
