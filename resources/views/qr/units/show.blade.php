<x-app-layout>
    <x-slot name="header"><div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center"><div><a href="{{ route('admin.qr-units.index') }}" class="text-sm font-semibold text-green-700">← QR Units</a><h2 class="mt-1 text-2xl font-bold text-gray-800">{{ $qrUnit->currentDestination?->place_name ?: $qrUnit->unit_code }}</h2></div><span class="w-fit rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold">{{ $qrUnit->status }}</span></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-5xl space-y-5 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
        <div class="grid gap-5 md:grid-cols-2">
            @php($unitUrl = $qrUnit->template->type === 'qr_nfc' ? route('qr.nfc.show', $qrUnit->token) : route('qr.public.show', $qrUnit->token))
            <section class="rounded-xl bg-white p-6 shadow-sm"><h3 class="text-lg font-bold">Identitas unit</h3><dl class="mt-4 space-y-3 text-sm"><div><dt class="text-gray-500">Template</dt><dd class="font-semibold">{{ $qrUnit->template->name }}</dd></div><div><dt class="text-gray-500">Klien</dt><dd class="font-semibold">{{ $qrUnit->client?->company_name ?: $qrUnit->client?->user?->name ?: 'Stok umum' }}</dd></div><div><dt class="text-gray-500">Unit code</dt><dd class="font-mono">{{ $qrUnit->unit_code }}</dd></div>@if($qrUnit->activation_code)<div><dt class="text-gray-500">Kode aktivasi (belum digunakan)</dt><dd class="font-mono font-bold">{{ $qrUnit->activation_code }}</dd></div>@endif<div><dt class="text-gray-500">Token</dt><dd class="break-all font-mono">{{ $qrUnit->token }}</dd></div><div><dt class="text-gray-500">URL permanen</dt><dd class="break-all"><a class="text-green-700 underline" href="{{ $unitUrl }}" target="_blank">{{ $unitUrl }}</a></dd></div>@if($qrUnit->template->type === 'qr_nfc')<div><dt class="text-gray-500">URL untuk ditulis ke NFC</dt><dd class="break-all font-mono">{{ $unitUrl }}</dd></div>@endif</dl>
                <div class="mt-6 flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.qr-units.destroy', $qrUnit) }}" onsubmit="return confirm('Hapus permanen unit ini? Destination dan riwayat scan juga akan dihapus.')">@csrf @method('DELETE')<button type="submit" style="background-color:#b91c1c;color:#ffffff;" class="rounded-lg px-4 py-2 text-sm font-bold">Hapus unit</button></form>
                    @if($qrUnit->status !== 'DISABLED')<form method="POST" action="{{ route('admin.qr-units.disable', $qrUnit) }}">@csrf<button class="rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">Nonaktifkan</button></form>@else<form method="POST" action="{{ route('admin.qr-units.enable', $qrUnit) }}">@csrf<button class="rounded-lg bg-green-50 px-4 py-2 text-sm font-semibold text-green-700">Aktifkan kembali</button></form>@endif
                    <form method="POST" action="{{ route('admin.qr-units.reset', $qrUnit) }}" onsubmit="return confirm('Arsipkan destination aktif dan kosongkan unit ini? URL QR tidak berubah.')">@csrf<button class="rounded-lg border px-4 py-2 text-sm font-semibold">Reset / pakai ulang</button></form>
                </div>
            </section>
            <section class="rounded-xl bg-white p-6 shadow-sm"><h3 class="text-lg font-bold">Destination aktif</h3>@php($current = $qrUnit->destinations->firstWhere('deactivated_at', null))
                @if($current)<dl class="mt-4 space-y-3 text-sm"><div><dt class="text-gray-500">Tempat</dt><dd class="font-semibold">{{ $current->place_name }}</dd></div><div><dt class="text-gray-500">Alamat</dt><dd>{{ $current->place_address }}</dd></div><div><dt class="text-gray-500">Place ID</dt><dd class="break-all font-mono">{{ $current->place_id }}</dd></div><div><dt class="text-gray-500">Review URL</dt><dd class="break-all"><a class="text-green-700 underline" target="_blank" href="{{ $current->review_url }}">{{ $current->review_url }}</a></dd></div></dl>@else<p class="mt-3 text-sm text-gray-500">Belum ada destination aktif.</p>@endif
            </section>
        </div>
        <section id="unit-qr-print" class="rounded-xl bg-white p-6 text-center shadow-sm">
            <h3 class="text-lg font-bold">{{ $qrUnit->template->type === 'qr_nfc' ? 'QR + NFC' : 'QR Code Unit' }}</h3>
            <p class="mt-1 text-sm text-gray-500">QR ini memiliki URL permanen dan unik untuk unit {{ $qrUnit->unit_code }}.</p>
            <div class="mx-auto mt-4 w-fit rounded-xl border border-gray-200 bg-white p-4">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(240)->margin(1)->generate($unitUrl) !!}
            </div>
            <p class="mt-3 break-all text-xs text-gray-500">{{ $unitUrl }}</p>
            <button type="button" onclick="window.print()" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="mt-4 inline-flex min-h-12 items-center justify-center rounded-lg px-6 py-3 font-bold shadow-sm hover:brightness-90">Cetak QR Unit</button>
        </section>
        <style>
            @media print {
                body * { visibility: hidden !important; }
                #unit-qr-print, #unit-qr-print * { visibility: visible !important; }
                #unit-qr-print { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; }
                #unit-qr-print button { display: none !important; }
            }
        </style>        <section class="overflow-hidden rounded-xl bg-white shadow-sm"><div class="border-b p-5"><h3 class="font-bold">Riwayat destination</h3><p class="mt-1 text-xs text-gray-500">Reset menonaktifkan destination lama dan mempertahankan catatannya.</p></div><div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Tempat</th><th class="px-5 py-3">Alamat</th><th class="px-5 py-3">Place ID</th><th class="px-5 py-3">Aktif</th><th class="px-5 py-3">Dinonaktifkan</th></tr></thead><tbody class="divide-y">@forelse($qrUnit->destinations as $destination)<tr><td class="px-5 py-3 font-semibold">{{ $destination->place_name }}</td><td class="px-5 py-3">{{ $destination->place_address }}</td><td class="px-5 py-3 font-mono text-xs">{{ $destination->place_id }}</td><td class="px-5 py-3">{{ $destination->activated_at?->format('d/m/Y H:i') ?: '—' }}</td><td class="px-5 py-3">{{ $destination->deactivated_at?->format('d/m/Y H:i') ?: 'Aktif' }}</td></tr>@empty<tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada riwayat.</td></tr>@endforelse</tbody></table></div></section>
    </div></div>
</x-app-layout>
