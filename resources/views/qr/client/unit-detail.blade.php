<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('client.units') }}" class="text-sm font-semibold text-green-700">← QR Saya</a>
                <h2 class="mt-1 text-2xl font-bold text-gray-800">{{ $qrUnit->currentDestination?->place_name ?: $qrUnit->unit_code }}</h2>
                <p class="mt-1 text-sm text-gray-500">Detail unit QR {{ $qrUnit->unit_code }}</p>
            </div>
            <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-bold">{{ $qrUnit->status }}</span>
        </div>
    </x-slot>

    @php($unitUrl = $qrUnit->template->type === 'qr_nfc' ? route('qr.nfc.show', $qrUnit->token) : route('qr.public.show', $qrUnit->token))
    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-5 md:grid-cols-3">
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Total scan</p><p class="mt-2 text-3xl font-bold">{{ number_format($scanCount) }}</p></article>
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Klik review</p><p class="mt-2 text-3xl font-bold">{{ number_format($reviewClickCount) }}</p></article>
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Rating rata-rata</p><p class="mt-2 text-3xl font-bold">{{ $averageRating !== null ? number_format((float) $averageRating, 1) . ' ★' : '—' }}</p></article>
            </section>

            <section class="grid gap-5 lg:grid-cols-2">
                <article class="rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold">Informasi QR</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div><dt class="text-gray-500">Label QR</dt><dd class="font-mono font-semibold">{{ $qrUnit->unit_code }}</dd></div>
                        <div><dt class="text-gray-500">Template</dt><dd class="font-semibold">{{ $qrUnit->template->name }}</dd></div>
                        <div><dt class="text-gray-500">Status</dt><dd class="font-semibold">{{ $qrUnit->status }}</dd></div>
                        <div><dt class="text-gray-500">Nama tempat aktif</dt><dd class="font-semibold">{{ $qrUnit->currentDestination?->place_name ?? 'Belum diaktivasi' }}</dd></div>
                        <div><dt class="text-gray-500">Alamat</dt><dd>{{ $qrUnit->currentDestination?->place_address ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">URL QR permanen</dt><dd class="break-all"><a href="{{ $unitUrl }}" target="_blank" class="text-green-700 underline">{{ $unitUrl }}</a></dd></div>
                        @if($qrUnit->template->type === 'qr_nfc')<div><dt class="text-gray-500">URL untuk NFC</dt><dd class="break-all font-mono">{{ $unitUrl }}</dd></div>@endif
                    </dl>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('client.units.print', $qrUnit) }}" style="background-color:#15803d;color:#ffffff;" class="rounded-lg px-4 py-2 text-sm font-bold">Cetak QR</a>
                        <a href="{{ $unitUrl }}" target="_blank" class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700">Buka halaman QR</a>
                    </div>
                </article>

                <article id="qr-print" class="rounded-xl bg-white p-6 text-center shadow-sm">
                    <h3 class="text-lg font-bold">Kode QR</h3>
                    <p class="mt-1 text-sm text-gray-500">Pindai untuk membuka halaman QR unit ini.</p>
                    <div class="mx-auto mt-4 w-fit rounded-xl border border-gray-200 bg-white p-4">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(250)->margin(1)->generate($unitUrl) !!}
                    </div>
                    <p class="mt-3 break-all text-xs text-gray-500">{{ $unitUrl }}</p>
                </article>
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b p-5"><h3 class="font-bold">Riwayat nama tempat</h3><p class="mt-1 text-sm text-gray-500">Perubahan lokasi yang pernah dihubungkan ke QR ini.</p></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Nama tempat</th><th class="px-5 py-3">Alamat</th><th class="px-5 py-3">Aktif sejak</th><th class="px-5 py-3">Status</th></tr></thead>
                        <tbody class="divide-y">
                            @forelse($qrUnit->destinations as $destination)
                                <tr><td class="px-5 py-3 font-semibold">{{ $destination->place_name }}</td><td class="px-5 py-3">{{ $destination->place_address ?: '—' }}</td><td class="px-5 py-3">{{ $destination->activated_at?->format('d/m/Y H:i') ?? '—' }}</td><td class="px-5 py-3">{{ $destination->deactivated_at ? 'Selesai ' . $destination->deactivated_at->format('d/m/Y') : 'Aktif' }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500">Belum ada riwayat tempat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b p-5"><h3 class="font-bold">Feedback terbaru</h3></div>
                <div class="divide-y">
                    @forelse($feedback as $item)
                        <article class="p-5"><div class="flex items-center justify-between gap-3"><p class="font-semibold">{{ $item->rating }} ★</p><time class="text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i') }}</time></div><p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $item->comment ?: 'Tanpa komentar' }}</p></article>
                    @empty
                        <p class="p-8 text-center text-sm text-gray-500">Belum ada feedback untuk QR ini.</p>
                    @endforelse
                </div>
                <div class="border-t p-4">{{ $feedback->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
