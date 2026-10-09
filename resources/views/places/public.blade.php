<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $place->name ?: 'Informasi Tempat' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-2xl items-center px-4 py-10">
        <article class="w-full rounded-2xl bg-white p-6 shadow-sm sm:p-10">
            <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl" aria-hidden="true">📍</div>
            @if ($place->qr_sale_status === 'pending_payment')
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">QR Template</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">QR belum aktif</h1>
                <p class="mt-3 leading-7 text-gray-600">QR ini sedang disiapkan untuk klien dan akan aktif setelah pembayaran dikonfirmasi.</p>
            @elseif (blank($place->name) && blank($place->address))
                <p class="text-sm font-semibold uppercase tracking-wide text-green-700">QR tempat baru</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Isi nama dan alamat tempat</h1>
                <p class="mt-3 leading-7 text-gray-600">Setelah disimpan, Anda akan diarahkan ke halaman Google Review. QR ini akan tetap sama dan langsung membuka link review tersebut saat dipindai lagi.</p>
                <form method="POST" action="{{ route('places.public.review', $place) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="place_name" class="block mb-1 text-sm font-semibold text-gray-700">Nama tempat</label>
                        <input id="place_name" name="place_name" value="{{ old('place_name') }}" required maxlength="255" autocomplete="organization"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Contoh: Solaria - Metro Indah Mall Bandung">
                        @error('place_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="place_address" class="block mb-1 text-sm font-semibold text-gray-700">Alamat atau link Google Maps</label>
                        <input id="place_address" name="place_address" type="text" value="{{ old('place_address') }}" required maxlength="2048" autocomplete="street-address"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Masukkan alamat atau tempel link Google Maps">
                        @error('place_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="block w-full rounded-lg bg-green-700 px-5 py-3 text-center font-semibold text-white hover:bg-green-800">Simpan dan buka Google Review</button>
                </form>
            @else
                <p class="text-sm font-semibold uppercase tracking-wide text-green-700">Informasi tempat</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ $place->name }}</h1>
                <p class="mt-4 whitespace-pre-line leading-7 text-gray-600">{{ $place->address }}</p>
            @endif
        </article>
    </main>
</body>
</html>
