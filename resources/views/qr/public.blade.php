<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $unit->template->name }} - QR Review</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .place-results{position:absolute;z-index:20;top:100%;left:0;right:0;max-height:280px;overflow:auto;border:1px solid #d1d5db;border-radius:10px;background:#fff;box-shadow:0 12px 28px #0002}
        .place-option{display:block;width:100%;padding:12px 14px;text-align:left;border:0;border-bottom:1px solid #f0f0f0;background:#fff;color:#111827}
        .place-option:hover,.place-option:focus{background:#f0fdf4;outline:none}
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-xl items-center px-4 py-10">
        <article class="w-full rounded-2xl bg-white p-6 shadow-sm sm:p-10">
            <p class="text-xs font-bold uppercase tracking-wide text-green-700">{{ $unit->template->name }} · {{ $unit->unit_code }}</p>
            @if($unit->status === 'EMPTY')
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Aktifkan QR untuk Google Review</h1>
                <p class="mt-3 leading-7 text-gray-600">Ketik nama tempat, pilih hasil Google Maps yang sesuai, lalu simpan. QR ini akan terhubung ke tempat yang dipilih.</p>
                <form id="activation-form" method="POST" action="{{ $activationUrl ?? route('qr.public.activate', $unit->token) }}" class="mt-6 space-y-4">@csrf
                    <input type="hidden" name="place_id" id="place_id" value="{{ old('place_id') }}">
                    <input type="hidden" name="session_token" id="session_token" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                    <div class="relative">
                        <label for="place_name" class="mb-1 block text-sm font-semibold">Nama tempat</label>
                        <input id="place_name" name="place_name" value="{{ old('place_name') }}" required maxlength="255" autocomplete="off" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Ketik nama tempat" aria-autocomplete="list" aria-controls="place-results">
                        @error('place_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <div id="place-results" class="place-results hidden" role="listbox"></div>
                    </div>
                    <div>
                        <label for="place_address" class="mb-1 block text-sm font-semibold">Alamat tempat</label>
                        <textarea id="place_address" name="place_address" rows="3" required maxlength="2048" readonly class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-green-500 focus:ring-green-500" placeholder="Alamat otomatis terisi setelah memilih tempat">{{ old('place_address') }}</textarea>
                        @error('place_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        @error('place_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="block min-h-12 w-full rounded-lg px-5 py-3 text-center text-base font-bold shadow-sm hover:brightness-90">Simpan &amp; lanjut ke Google Review</button>
                </form>
                <div class="mt-3 flex justify-end"><img src="https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3.png" alt="Powered by Google" width="120" height="14"></div>
            @elseif($unit->status === 'PENDING')
                <h1 class="mt-2 text-2xl font-bold">QR sedang diproses</h1><p class="mt-3 text-gray-600">Pencarian tempat sedang berlangsung. Coba pindai kembali sebentar lagi.</p>
            @elseif($unit->status === 'DISABLED')
                <h1 class="mt-2 text-2xl font-bold">QR tidak aktif</h1><p class="mt-3 text-gray-600">Hubungi pihak yang memberikan QR ini.</p>
            @else
                <h1 class="mt-2 text-2xl font-bold">QR belum siap</h1><p class="mt-3 text-gray-600">Destination belum tersedia. Coba pindai kembali.</p>
            @endif
        </article>
    </main>
    @if($unit->status === 'EMPTY')
    <script>
        (() => {
            const nameInput = document.getElementById('place_name');
            const addressInput = document.getElementById('place_address');
            const placeIdInput = document.getElementById('place_id');
            const sessionInput = document.getElementById('session_token');
            const results = document.getElementById('place-results');
            const csrf = document.querySelector('input[name="_token"]').value;
            let timer;
            let request;

            // A failed form round-trip starts a fresh autocomplete session; the place must be selected again.
            placeIdInput.value = '';

            function closeResults() { results.replaceChildren(); results.classList.add('hidden'); }
            nameInput.addEventListener('input', () => {
                placeIdInput.value = '';
                addressInput.value = '';
                clearTimeout(timer);
                if (request) request.abort();
                const query = nameInput.value.trim();
                if (query.length < 3) { closeResults(); return; }
                timer = setTimeout(async () => {
                    request = new AbortController();
                    try {
                        const response = await fetch(@json(route('qr.places.autocomplete')), {
                            method: 'POST', signal: request.signal,
                            headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},
                            body: JSON.stringify({query, session_token:sessionInput.value})
                        });
                        const data = await response.json();
                        closeResults();
                        for (const place of (data.suggestions || [])) {
                            const option = document.createElement('button');
                            option.type = 'button'; option.className = 'place-option'; option.setAttribute('role','option');
                            const title = document.createElement('span'); title.className = 'block font-semibold'; title.textContent = place.name;
                            const address = document.createElement('span'); address.className = 'mt-1 block text-sm text-gray-600'; address.textContent = place.address;
                            option.append(title, address);
                            option.addEventListener('click', () => {
                                nameInput.value = place.name;
                                addressInput.value = place.address;
                                placeIdInput.value = place.place_id;
                                closeResults();
                            });
                            results.append(option);
                        }
                        if (results.childElementCount) results.classList.remove('hidden');
                        else if (response.ok) {
                            const empty = document.createElement('p'); empty.className = 'p-3 text-sm text-gray-600';
                            empty.textContent = 'Tempat tidak ditemukan. Coba kata kunci lain.'; results.append(empty); results.classList.remove('hidden');
                        } else {
                            const error = document.createElement('p'); error.className = 'p-3 text-sm text-red-600';
                            error.textContent = data.message || 'Saran Google Maps gagal dimuat.'; results.append(error); results.classList.remove('hidden');
                        }
                    } catch (error) { if (error.name !== 'AbortError') closeResults(); }
                }, 300);
            });
            document.getElementById('activation-form').addEventListener('submit', event => {
                if (!placeIdInput.value) {
                    event.preventDefault();
                    nameInput.focus();
                    alert('Pilih salah satu tempat dari daftar saran Google Maps.');
                }
            });
            document.addEventListener('click', event => { if (!results.contains(event.target) && event.target !== nameInput) closeResults(); });
        })();
    </script>
    @endif
</body>
</html>
