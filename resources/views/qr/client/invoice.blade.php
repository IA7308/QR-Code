<x-app-layout>
    <x-slot name="header"><div><a href="{{ route('client.dashboard') }}" class="text-sm font-semibold text-green-700">← Portal Klien</a><h2 class="mt-1 text-2xl font-bold text-gray-800">Invoice {{ $qrInvoice->invoice_number }}</h2></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
        <section class="rounded-xl bg-white p-6 shadow-sm"><div class="grid gap-5 sm:grid-cols-2"><div><p class="text-sm text-gray-500">Paket</p><p class="font-bold">{{ $qrInvoice->subscription->plan->name }}</p></div><div><p class="text-sm text-gray-500">Status invoice</p><p class="font-bold">{{ $qrInvoice->status }}</p></div><div><p class="text-sm text-gray-500">Jumlah</p><p class="text-xl font-extrabold">Rp {{ number_format($qrInvoice->amount, 0, ',', '.') }}</p></div><div><p class="text-sm text-gray-500">Jatuh tempo</p><p class="font-semibold">{{ $qrInvoice->due_at?->format('d M Y') ?? '—' }}</p></div></div>
            <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900"><strong>Instruksi pembayaran</strong><p class="mt-1 whitespace-pre-line">{{ config('services.qr_billing.instructions') }}</p><p class="mt-2">Pastikan nominal transfer sesuai invoice, lalu unggah bukti pembayaran di bawah.</p></div>
            @if($qrInvoice->proof_path)<p class="mt-4 text-sm">Bukti terkirim: <a class="font-semibold text-green-700 underline" href="{{ route('client.invoices.proof-file', $qrInvoice) }}">Lihat file</a></p>@endif
            @if($qrInvoice->admin_note)<div class="mt-4 rounded-lg bg-amber-50 p-4 text-sm text-amber-900"><strong>Catatan admin</strong><p>{{ $qrInvoice->admin_note }}</p></div>@endif
            @if(in_array($qrInvoice->status, ['PENDING', 'REJECTED'], true))<form method="POST" enctype="multipart/form-data" action="{{ route('client.invoices.proof', $qrInvoice) }}" class="mt-6 space-y-4 border-t pt-5">@csrf<div><label for="proof" class="block text-sm font-semibold text-gray-700">Bukti transfer (JPG, PNG, PDF; maks. 5 MB)</label><input id="proof" name="proof" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="mt-2 block w-full rounded-lg border border-gray-300 p-2 text-sm"><x-input-error :messages="$errors->get('proof')" class="mt-2"/></div><div><label for="client_note" class="block text-sm font-semibold text-gray-700">Catatan (opsional)</label><textarea id="client_note" name="client_note" rows="2" maxlength="1000" class="mt-2 block w-full rounded-lg border-gray-300">{{ old('client_note', $qrInvoice->client_note) }}</textarea></div><button type="submit" style="background-color:#15803d;color:#ffffff;" class="rounded-lg px-5 py-3 font-bold">Kirim bukti pembayaran</button></form>@endif
            @if($qrInvoice->subscription->status === 'PENDING')
                <form method="POST" action="{{ route('client.subscriptions.cancel', $qrInvoice->subscription) }}" class="mt-5 border-t pt-4">@csrf
                    <button type="submit" onclick="return confirm('Batalkan pengajuan subscription ini?')" class="text-sm font-semibold text-red-700 underline">Batalkan pengajuan subscription</button>
                </form>
            @endif
        </section>
    </div></div>
</x-app-layout>
