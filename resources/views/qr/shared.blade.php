<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $unit->template->name }} - Pilih lokasi review</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .suggestions{position:absolute;z-index:20;top:100%;left:0;right:0;max-height:280px;overflow:auto;border:1px solid #d1d5db;border-radius:10px;background:#fff;box-shadow:0 12px 28px #0002}
        .suggestion{display:block;width:100%;padding:12px 14px;text-align:left;border:0;border-bottom:1px solid #f0f0f0;background:#fff;color:#111827}
        .suggestion:hover,.suggestion:focus{background:#f0fdf4;outline:none}
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<main class="mx-auto min-h-screen max-w-xl px-4 py-8 sm:py-12">
    <article class="rounded-2xl bg-white p-6 shadow-sm sm:p-9">
        <p class="text-xs font-bold uppercase tracking-wide text-green-700">{{ $unit->template->name }}</p>
        <h1 class="mt-2 text-2xl font-bold">Pilih lokasi Google Review</h1>
        <p class="mt-2 text-sm leading-6 text-gray-600">QR ini dipakai bersama. Pilih lokasi yang ingin Anda ulas; pilihan akan diverifikasi sebelum membuka Google Review.</p>

        @if(session('success'))<div class="mt-4 rounded-lg bg-green-50 p-3 text-sm text-green-800">{{ session('success') }}</div>@endif
        @error('location')<div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div>@enderror

        <div class="mt-6">
            <label for="location-search" class="mb-1 block text-sm font-semibold">Cari nama atau alamat tempat</label>
            <input id="location-search" type="search" class="w-full rounded-lg border-gray-300" placeholder="Ketik nama tempat">
        </div>
        <div id="location-list" class="mt-3 space-y-2">
            @forelse($locations as $location)
                <form method="POST" action="{{ route('qr.shared.select', $unit->token) }}" class="location-row rounded-xl border border-gray-200">@csrf
                    <input type="hidden" name="activation_code" value="{{ $location->activation_code }}">
                    <button type="submit" class="w-full rounded-xl p-4 text-left hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600">
                        <span class="block font-semibold text-gray-900">{{ $location->place_name }}</span>
                        <span class="mt-1 block text-sm text-gray-600">{{ $location->place_address }}</span>
                        <span class="mt-2 block text-xs font-bold text-green-700">BUKA GOOGLE REVIEW →</span>
                    </button>
                </form>
            @empty
                <p class="rounded-lg bg-amber-50 p-4 text-sm text-amber-900">Belum ada lokasi yang terdaftar pada QR ini. Tambahkan lokasi terlebih dahulu.</p>
            @endforelse
            <p id="no-location-match" class="hidden rounded-lg bg-gray-50 p-4 text-sm text-gray-600">Tidak ada lokasi yang cocok.</p>
        </div>

        <details class="mt-8 rounded-xl border border-gray-200 p-4">
            <summary class="cursor-pointer font-semibold text-gray-800">Tambah lokasi baru</summary>
            <p class="mt-2 text-sm text-gray-600">Pilih tempat dari saran Google Maps. Kode aktivasi lokasi dibuat otomatis.</p>
            <form id="shared-activation-form" method="POST" action="{{ route('qr.public.activate', $unit->token) }}" class="mt-4 space-y-4">@csrf
                <input type="hidden" name="place_id" id="place_id">
                <input type="hidden" name="session_token" id="session_token" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                <div class="relative">
                    <label for="place_name" class="mb-1 block text-sm font-semibold">Nama tempat</label>
                    <input id="place_name" name="place_name" value="{{ old('place_name') }}" required maxlength="255" autocomplete="off" class="w-full rounded-lg border-gray-300" placeholder="Ketik nama tempat">
                    @error('place_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('place_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div id="place-suggestions" class="suggestions hidden" role="listbox"></div>
                </div>
                <div>
                    <label for="place_address" class="mb-1 block text-sm font-semibold">Alamat tempat</label>
                    <textarea id="place_address" name="place_address" rows="3" required readonly class="w-full rounded-lg border-gray-300 bg-gray-50" placeholder="Terisi setelah memilih tempat">{{ old('place_address') }}</textarea>
                    @error('place_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" style="background-color:#15803d;color:#ffffff;" class="w-full rounded-lg px-4 py-3 font-bold">Simpan lokasi</button>
                <div class="flex justify-end"><img src="https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3.png" alt="Powered by Google" width="120" height="14"></div>
            </form>
        </details>
    </article>
</main>
<script>
(() => {
    const filter = document.getElementById('location-search');
    const rows = [...document.querySelectorAll('.location-row')];
    const noMatch = document.getElementById('no-location-match');
    filter.addEventListener('input', () => {
        const query = filter.value.trim().toLowerCase();
        let visible = 0;
        rows.forEach(row => { const match = row.textContent.toLowerCase().includes(query); row.classList.toggle('hidden', !match); if (match) visible++; });
        noMatch.classList.toggle('hidden', visible > 0 || rows.length === 0);
    });

    const nameInput = document.getElementById('place_name');
    const addressInput = document.getElementById('place_address');
    const placeIdInput = document.getElementById('place_id');
    const sessionInput = document.getElementById('session_token');
    const suggestions = document.getElementById('place-suggestions');
    const form = document.getElementById('shared-activation-form');
    const csrf = form.querySelector('input[name="_token"]').value;
    let timer; let activeRequest;
    const closeSuggestions = () => { suggestions.replaceChildren(); suggestions.classList.add('hidden'); };

    nameInput.addEventListener('input', () => {
        placeIdInput.value = ''; addressInput.value = '';
        clearTimeout(timer); if (activeRequest) activeRequest.abort();
        const query = nameInput.value.trim();
        if (query.length < 3) { closeSuggestions(); return; }
        timer = setTimeout(async () => {
            activeRequest = new AbortController();
            try {
                const response = await fetch(@json(route('qr.places.autocomplete')), {
                    method: 'POST', signal: activeRequest.signal,
                    headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},
                    body: JSON.stringify({query, session_token:sessionInput.value})
                });
                const data = await response.json(); closeSuggestions();
                for (const place of (data.suggestions || [])) {
                    const button = document.createElement('button'); button.type = 'button'; button.className = 'suggestion'; button.setAttribute('role','option');
                    const title = document.createElement('span'); title.className = 'block font-semibold'; title.textContent = place.name;
                    const address = document.createElement('span'); address.className = 'mt-1 block text-sm text-gray-600'; address.textContent = place.address;
                    button.append(title,address);
                    button.addEventListener('click', () => { nameInput.value = place.name; addressInput.value = place.address; placeIdInput.value = place.place_id; closeSuggestions(); });
                    suggestions.append(button);
                }
                if (suggestions.childElementCount) suggestions.classList.remove('hidden');
                else {
                    const message = document.createElement('p'); message.className = 'p-3 text-sm text-gray-600';
                    message.textContent = response.ok ? 'Tempat tidak ditemukan.' : (data.message || 'Saran Google Maps gagal dimuat.');
                    suggestions.append(message); suggestions.classList.remove('hidden');
                }
            } catch (error) { if (error.name !== 'AbortError') closeSuggestions(); }
        }, 300);
    });
    form.addEventListener('submit', event => { if (!placeIdInput.value) { event.preventDefault(); nameInput.focus(); alert('Pilih tempat dari saran Google Maps.'); } });
    document.addEventListener('click', event => { if (!suggestions.contains(event.target) && event.target !== nameInput) closeSuggestions(); });
})();
</script>
</body>
</html>
