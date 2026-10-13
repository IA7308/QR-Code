<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR berhasil diaktifkan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-xl items-center px-4 py-10">
        <article class="w-full rounded-2xl bg-white p-6 shadow-sm sm:p-10">
            <p class="text-xs font-bold uppercase tracking-wide text-green-700">{{ $unit->template->name }} · {{ $unit->unit_code }}</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">QR berhasil diaktifkan</h1>
            <p class="mt-3 leading-7 text-gray-600">QR sudah dikaitkan dengan akun klien dan tetap aktif tanpa paket. Simpan data akun berikut; password dan PIN hanya ditampilkan di halaman ini.</p>
            <dl class="mt-5 space-y-3 rounded-xl bg-gray-50 p-4 text-sm">
                <div><dt class="font-semibold">Username{{ $credentials['username_generated'] ? ' (acak)' : '' }}</dt><dd class="mt-1 break-all font-mono">{{ $credentials['username'] }}</dd></div>
                <div><dt class="font-semibold">Password{{ $credentials['password_generated'] ? ' (acak)' : '' }}</dt><dd class="mt-1 break-all font-mono">{{ $credentials['password'] }}</dd></div>
                <div><dt class="font-semibold">Nomor HP{{ $credentials['phone_generated'] ? ' (acak, nomor dummy)' : '' }}</dt><dd class="mt-1 break-all font-mono">{{ $credentials['phone'] }}</dd></div>
                <div><dt class="font-semibold">PIN{{ $credentials['pin_generated'] ? ' (acak)' : '' }}</dt><dd class="mt-1 break-all font-mono">{{ $credentials['pin'] }}</dd></div>
            </dl>
            <div class="mt-6 space-y-3">
                @if($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" style="background-color:#15803d;color:#ffffff;" class="block rounded-lg px-5 py-3 text-center font-bold">Buka WhatsApp dan kirim data</a>
                @else
                    <p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Nomor HP dummy dibuat acak karena kolom dikosongkan, jadi WhatsApp tidak dapat dibuka. Untuk mengirim data ke WhatsApp, isi nomor HP aktif saat aktivasi.</p>
                @endif
                <a href="{{ $reviewUrl }}" style="background-color:#ffffff;color:#15803d;border:1px solid #15803d;" class="block rounded-lg px-5 py-3 text-center font-bold">Lanjut ke Google Review</a>
            </div>
            @if($whatsappUrl)
                <p class="mt-4 text-center text-xs text-gray-500">WhatsApp membuka pesan berisi username, nomor, lokasi, dan alamat. Tekan tombol kirim di WhatsApp. Password dan PIN tidak disertakan.</p>
            @endif
        </article>
    </main>
</body>
</html>
