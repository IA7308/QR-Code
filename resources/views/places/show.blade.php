<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center print:hidden">
            <div>
                <a href="{{ route('places.index') }}" class="text-sm font-medium text-green-700 hover:underline">← Daftar Tempat</a>
                <h2 class="mt-1 text-2xl font-bold text-gray-800">{{ $place->name ?: 'QR Tempat #' . $place->id }}</h2>
            </div>
            <a href="{{ route('places.edit', $place) }}" class="px-4 py-2 text-sm font-bold text-gray-700 border rounded-lg hover:bg-gray-50">Edit tempat</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="p-4 mb-5 text-sm font-medium text-green-800 bg-green-100 rounded-lg print:hidden">{{ session('success') }}</div>@endif
            @if(in_array($place->qr_sale_status, ['pending_payment', 'paid'], true))
                <section class="mb-6 rounded-xl border bg-white p-5 shadow-sm print:hidden">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Penjualan QR Template</p>
                            <h3 class="mt-1 text-lg font-bold text-gray-900">
                                {{ $place->qr_sale_status === 'paid' ? 'Pembayaran lunas · QR siap diberikan' : 'Menunggu pembayaran' }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">Klien: {{ $place->buyer_name ?: 'Belum diisi' }} · {{ $place->buyer_phone ?: 'Nomor belum diisi' }}</p>
                            <p class="mt-1 text-sm text-gray-600">Harga: {{ $place->sale_price ? 'Rp ' . number_format($place->sale_price, 0, ',', '.') : 'Belum diisi' }}</p>
                            @if($place->paid_at)<p class="mt-1 text-xs text-gray-500">Dikonfirmasi {{ $place->paid_at->format('d/m/Y H:i') }}</p>@endif
                        </div>
                        @if($place->qr_sale_status === 'pending_payment')
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('places.edit', $place) }}" class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Isi / edit data penjualan</a>
                                @if(filled($place->buyer_name) && filled($place->buyer_phone) && $place->sale_price > 0 && filled($place->google_review_url))
                                    <form method="POST" action="{{ route('places.template.confirm-payment', $place) }}" onsubmit="return confirm('Konfirmasi pembayaran diterima dan aktifkan QR untuk klien?')">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-green-700 px-4 py-2 text-sm font-bold text-white hover:bg-green-800">Konfirmasi pembayaran</button>
                                    </form>
                                @else
                                    <span class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-500">Lengkapi data sebelum konfirmasi</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </section>
            @endif
            <div class="grid gap-6 md:grid-cols-5">
                <section class="p-6 bg-white shadow-sm md:col-span-3 sm:rounded-xl">
                    <p class="text-xs font-bold tracking-wide text-gray-500 uppercase">Informasi Tempat</p>
                    <h1 class="mt-2 text-2xl font-black text-gray-900">{{ $place->name ?: 'Tempat belum diisi' }}</h1>
                    <p class="mt-3 text-gray-600">{{ $place->address ?: 'Isi nama dan alamat tempat. QR tetap sudah aktif dan tidak akan berubah.' }}</p>
                    @if($place->description)<p class="mt-4 text-sm leading-relaxed text-gray-600">{{ $place->description }}</p>@endif
                    <div class="flex flex-wrap gap-3 mt-6 print:hidden">
                        @if($place->google_maps_url)<a href="{{ $place->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 text-sm font-bold text-blue-800 bg-blue-50 rounded-lg hover:bg-blue-100">Buka Google Maps ↗</a>@endif
                        @if($place->google_review_url)<a href="{{ $place->google_review_url }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 text-sm font-bold text-green-800 bg-green-50 rounded-lg hover:bg-green-100">Tulis Google Review ↗</a>@endif
                    </div>
                </section>

                <section class="p-6 text-center bg-white shadow-sm md:col-span-2 sm:rounded-xl">
                    <h3 class="text-lg font-bold text-gray-900">QR Template Tetap</h3>
                    <p class="mt-1 text-sm text-gray-500">Gambar QR dan URL ini tetap sama sebelum maupun sesudah data review tempat diisi.</p>
                    <div class="flex justify-center p-4 mt-4 bg-white border rounded-xl" id="place-qr">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(260)->margin(2)->generate(route('places.public', $place)) !!}</div>
                    <p class="mt-3 break-all text-xs text-gray-500">{{ route('places.public', $place) }}</p>
                    <div class="flex flex-wrap justify-center gap-2 mt-5 print:hidden">
                        @if($place->qr_sale_status !== 'pending_payment')
                            <a href="{{ route('places.qr.download', $place) }}" class="px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">Unduh QR (SVG)</a>
                        @else
                            <span class="px-4 py-2 text-sm font-bold text-gray-500 bg-gray-100 rounded-lg">Unduhan terbuka setelah pembayaran</span>
                        @endif
                        <a href="{{ route('places.edit', $place) }}" class="px-4 py-2 text-sm font-bold text-gray-700 border rounded-lg hover:bg-gray-50">Isi / Edit Tempat</a>
                        @if($place->qr_sale_status !== 'pending_payment')
                            <button type="button" onclick="window.print()" class="px-4 py-2 text-sm font-bold text-gray-700 border rounded-lg hover:bg-gray-50">Cetak QR</button>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
    <style>
        @media print {
            body { background: #fff !important; }
            aside, nav, header, footer, button, a { visibility: hidden !important; }
            #place-qr, #place-qr *, h1, h3, p { visibility: visible !important; }
            #place-qr { position: fixed; inset: 15% 0 auto; border: 0; }
        }
    </style>
</x-app-layout>
