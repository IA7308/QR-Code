<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tempat & QR Tetap</h2>
                <p class="mt-1 text-sm text-gray-500">Buat QR template untuk klien. Konfigurasikan tempat dan konfirmasi pembayaran sebelum menyerahkan QR.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(Auth::user()->role === 'admin')
                    <form method="POST" action="{{ route('places.drafts.store') }}">@csrf<button class="inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-green-600 rounded-xl hover:bg-green-700">+ Buat QR Template</button></form>
                @endif
                <a href="{{ route('places.create') }}" class="inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-gray-700 border rounded-xl hover:bg-gray-50">Isi tempat sekarang</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl px-4 mx-auto space-y-5 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 text-sm font-medium text-green-800 bg-green-100 rounded-lg">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ route('places.index') }}" class="flex flex-col gap-3 p-4 bg-white shadow-sm sm:flex-row sm:rounded-xl">
                <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau alamat tempat..." class="flex-1 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                <button class="px-5 py-2 text-sm font-semibold text-white bg-gray-800 rounded-lg hover:bg-gray-700">Cari</button>
                @if(request()->filled('search'))<a href="{{ route('places.index') }}" class="px-4 py-2 text-sm text-center text-gray-600 border rounded-lg">Reset</a>@endif
            </form>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                @if($places->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <div class="mb-3 text-4xl">📍</div>
                        <h3 class="text-lg font-bold text-gray-800">Belum ada tempat</h3>
                        <p class="mt-1 text-sm text-gray-500">Buat QR tetap, lalu isi nama dan alamat tempat kapan saja.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs tracking-wide text-gray-500 uppercase bg-gray-50">
                                <tr><th class="px-6 py-4">Tempat / Klien</th><th class="px-6 py-4">Status QR</th><th class="px-6 py-4">Tautan Google</th><th class="px-6 py-4">Dibuat oleh</th><th class="px-6 py-4 text-right">Aksi</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($places as $place)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4"><a class="font-bold text-gray-900 hover:text-green-700" href="{{ route('places.show', $place) }}">{{ $place->name ?: 'QR Template #' . $place->id }}</a><div class="mt-1 text-xs text-gray-500">{{ $place->address ?: 'Belum diisi' }}</div>@if($place->buyer_name)<div class="mt-1 text-xs text-gray-500">Klien: {{ $place->buyer_name }}</div>@endif</td>
                                        <td class="px-6 py-4">
                                            @if($place->qr_sale_status === 'pending_payment')<span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Menunggu pembayaran</span>
                                            @elseif($place->qr_sale_status === 'paid')<span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800">Lunas · siap diberikan</span>
                                            @else<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">Aktif</span>@endif
                                        </td>
                                        <td class="px-6 py-4"><div class="flex flex-wrap gap-2">
                                            @if($place->google_maps_url)<a href="{{ $place->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:underline">Buka Maps ↗</a>@endif
                                            @if($place->google_review_url)<a href="{{ $place->google_review_url }}" target="_blank" rel="noopener noreferrer" class="text-green-700 hover:underline">Tulis Review ↗</a>@endif
                                            @if(!$place->google_maps_url && !$place->google_review_url)<span class="text-gray-400">Belum diatur</span>@endif
                                        </div></td>
                                        <td class="px-6 py-4 text-gray-600">{{ $place->user->name }}</td>
                                        <td class="px-6 py-4"><div class="flex items-center justify-end gap-3 whitespace-nowrap">
                                            <a href="{{ route('places.show', $place) }}" class="font-semibold text-green-700 hover:underline">QR / Lihat</a>
                                            <a href="{{ route('places.edit', $place) }}" class="font-semibold text-gray-600 hover:underline">Edit</a>
                                            @if(Auth::user()->role === 'admin')
                                                <form method="POST" action="{{ route('places.destroy', $place) }}" onsubmit="return confirm('Hapus tempat ini?')">@csrf @method('DELETE')<button class="font-semibold text-red-600 hover:underline">Hapus</button></form>
                                            @endif
                                        </div></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t">{{ $places->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
