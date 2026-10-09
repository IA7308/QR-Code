<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-amber-400 to-orange-600">
                <span class="text-2xl">🌾</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-800">
                    {{ __('Catat Pakan Harian') }}
                </h2>
                <p class="text-sm font-medium text-amber-600">Form pencatatan pemberian pakan domba</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-amber-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- ERROR MESSAGES --}}
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 mb-6 text-red-800 border-l-4 border-red-500 rounded-lg shadow-sm bg-red-50">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-bold">Error!</span> {{ session('error') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-5 mb-6 border-l-4 border-red-500 rounded-lg shadow-sm bg-red-50">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-bold text-red-800">Ada kesalahan pada form:</span>
                    </div>
                    <ul class="pl-5 space-y-1 text-sm font-medium text-red-700 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('feeding-records.store') }}" class="space-y-6">
                @csrf

                {{-- SECTION 1: INFORMASI DASAR --}}
                <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                    <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-blue-500 to-indigo-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Dasar</h3>
                            <p class="text-xs text-blue-600">Tanggal, kandang, dan petugas pencatat</p>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            {{-- Tanggal --}}
                            <div>
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Tanggal Pakan <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="date"
                                    name="date"
                                    value="{{ old('date', date('Y-m-d')) }}"
                                    required
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none"
                                />
                            </div>

                            {{-- Kandang --}}
                            <div>
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Pilih Kandang <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="shelter_id"
                                    name="shelter_id"
                                    required
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none"
                                >
                                    <option value="">-- Pilih Kandang --</option>
                                    @foreach($shelters as $shelter)
                                        <option value="{{ $shelter->id }}" {{ old('shelter_id') == $shelter->id ? 'selected' : '' }}>
                                            {{ $shelter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Petugas --}}
                            <div>
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Dicatat Oleh
                                </label>
                                <div class="flex items-center px-4 py-2.5 text-sm font-bold text-gray-700 border-2 border-gray-200 bg-gray-50 rounded-xl">
                                    {{ Auth::user()->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: WAKTU PEMBERIAN --}}
                <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                    <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-orange-50">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-orange-500 to-amber-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Waktu Pemberian</h3>
                            <p class="text-xs text-orange-600">Jam pemberian pakan pagi & sore (opsional)</p>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            {{-- Waktu Pagi --}}
                            <div class="p-4 transition-all border-2 border-orange-200 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl hover:shadow-md">
                                <label class="flex items-center gap-2 mb-3 text-sm font-bold text-orange-800">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-orange-400 to-yellow-500">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    Waktu Pagi
                                </label>
                                <input
                                    type="time"
                                    id="time_morning"
                                    name="time_morning"
                                    value="{{ old('time_morning') }}"
                                    class="w-full px-4 py-2.5 text-sm font-semibold text-gray-800 transition-all border-2 border-orange-300 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 focus:outline-none"
                                />
                                <p class="mt-2 text-xs text-orange-600">Contoh: 07:00</p>
                            </div>

                            {{-- Waktu Sore --}}
                            <div class="p-4 transition-all border-2 border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl hover:shadow-md">
                                <label class="flex items-center gap-2 mb-3 text-sm font-bold text-blue-800">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                        </svg>
                                    </div>
                                    Waktu Sore
                                </label>
                                <input
                                    type="time"
                                    id="time_evening"
                                    name="time_evening"
                                    value="{{ old('time_evening') }}"
                                    class="w-full px-4 py-2.5 text-sm font-semibold text-gray-800 transition-all border-2 border-blue-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none"
                                />
                                <p class="mt-2 text-xs text-blue-600">Contoh: 16:00 (Harus setelah pagi)</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: DETAIL PAKAN --}}
                <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                    <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Jenis & Jumlah Pakan</h3>
                            <p class="text-xs text-green-600">Tambahkan jenis pakan dan porsi pagi/sore</p>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Template Hidden --}}
                        <div id="feed-item-template" class="hidden p-5 transition-all border-2 border-gray-200 bg-gray-50 rounded-xl hover:shadow-md">
                            {{-- Pilih Jenis Pakan --}}
                            <div class="mb-4">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Jenis Pakan <span class="text-red-500">*</span>
                                </label>
                                <select
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 feed-id-input rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                                    required
                                    disabled
                                >
                                    <option value="">-- Pilih Pakan --</option>
                                    @foreach($feedTypes as $feed)
                                        <option value="{{ $feed->id }}" data-unit="{{ $feed->unit }}">
                                            {{ $feed->name }} - Rp {{ number_format($feed->price_per_unit, 0, ',', '.') }}/{{ $feed->unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah Pakan --}}
                            <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                                {{-- Porsi Pagi --}}
                                <div class="p-3 border-2 border-orange-200 bg-orange-50 rounded-xl">
                                    <label class="block mb-2 text-xs font-bold text-orange-700 uppercase">
                                        ☀️ Porsi Pagi
                                    </label>
                                    <div class="flex items-center gap-1">
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            placeholder="0"
                                            disabled
                                            class="flex-1 w-0 min-w-0 px-2 py-2 text-base font-bold text-center text-gray-800 transition-all border-2 border-gray-200 rounded-lg feed-morning-input focus:border-orange-500 focus:ring-2 focus:ring-orange-100 focus:outline-none"
                                        />
                                        <span class="flex-shrink-0 px-2 py-2 text-xs font-bold text-orange-700 bg-orange-100 border-2 border-orange-300 rounded-lg feed-unit-display-morning">
                                            KG
                                        </span>
                                    </div>
                                </div>

                                {{-- Porsi Sore --}}
                                <div class="p-3 border-2 border-blue-200 bg-blue-50 rounded-xl">
                                    <label class="block mb-2 text-xs font-bold text-blue-700 uppercase">
                                        🌙 Porsi Sore
                                    </label>
                                    <div class="flex items-center gap-1">
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            placeholder="0"
                                            disabled
                                            class="flex-1 w-0 min-w-0 px-2 py-2 text-base font-bold text-center text-gray-800 transition-all border-2 border-gray-200 rounded-lg feed-evening-input focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none"
                                        />
                                        <span class="flex-shrink-0 px-2 py-2 text-xs font-bold text-blue-700 bg-blue-100 border-2 border-blue-300 rounded-lg feed-unit-display-evening">
                                            KG
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total & Hapus --}}
                            <div class="flex items-center justify-between p-3 bg-white border-2 border-gray-200 rounded-xl">
                                <div>
                                    <span class="text-xs font-semibold text-gray-500 uppercase">Total:</span>
                                    <span class="ml-2 text-lg font-black text-green-600">
                                        <span class="total-quantity">0.00</span>
                                        <span class="text-sm unit-label">KG</span>
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    onclick="removeFeedItem(this)"
                                    class="flex items-center gap-1 px-4 py-2 text-xs font-bold text-red-700 transition-all border-2 border-red-200 rounded-lg bg-red-50 hover:bg-red-100 hover:scale-105"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>

                        {{-- Container untuk item yang ditambahkan --}}
                        <div id="feed-items-list" class="mb-4 space-y-4">
                            {{-- Items akan muncul di sini --}}
                            @error('feed_types')
                                <div class="p-4 border-l-4 border-red-500 rounded-lg bg-red-50">
                                    <p class="text-sm font-bold text-red-700">⚠️ Anda wajib mengisi minimal satu jenis pakan dengan porsi (pagi atau sore)!</p>
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Tambah Pakan --}}
                        <button
                            type="button"
                            onclick="addFeedItem()"
                            class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Jenis Pakan
                        </button>

                        <p id="feed-error" class="hidden mt-3 text-sm font-bold text-center text-red-600">
                            ⚠️ Mohon tambahkan minimal satu jenis pakan!
                        </p>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex items-center justify-end gap-4 p-6 bg-white border-t-2 border-gray-100 shadow-lg rounded-2xl">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-gray-700 transition-all duration-200 bg-gray-100 border-2 border-gray-200 rounded-xl hover:bg-gray-200 hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-8 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-amber-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data Pakan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        let feedItemCount = 0;

        function updateTotal(itemDiv) {
            const morningInput = itemDiv.querySelector('.feed-morning-input');
            const eveningInput = itemDiv.querySelector('.feed-evening-input');
            const totalSpan = itemDiv.querySelector('.total-quantity');

            const morning = parseFloat(morningInput.value) || 0;
            const evening = parseFloat(eveningInput.value) || 0;
            const total = (morning + evening).toFixed(2);

            totalSpan.textContent = total;
        }

        function updateUnitDisplay(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const unit = selectedOption.getAttribute('data-unit') || 'KG';

            const itemDiv = selectElement.closest('.p-5');
            const unitDisplayMorning = itemDiv.querySelector('.feed-unit-display-morning');
            const unitDisplayEvening = itemDiv.querySelector('.feed-unit-display-evening');
            const unitLabel = itemDiv.querySelector('.unit-label');

            if (unitDisplayMorning) unitDisplayMorning.textContent = unit;
            if (unitDisplayEvening) unitDisplayEvening.textContent = unit;
            if (unitLabel) unitLabel.textContent = unit;
        }

        function addFeedItem() {
            const template = document.getElementById('feed-item-template');
            const clone = template.cloneNode(true);
            const currentCount = feedItemCount++;

            clone.id = `feed-item-${currentCount}`;
            clone.classList.remove('hidden');

            const feedIdInput = clone.querySelector('.feed-id-input');
            const morningInput = clone.querySelector('.feed-morning-input');
            const eveningInput = clone.querySelector('.feed-evening-input');

            feedIdInput.removeAttribute('disabled');
            morningInput.removeAttribute('disabled');
            eveningInput.removeAttribute('disabled');

            feedIdInput.name = `feed_types[${currentCount}][id]`;
            morningInput.name = `feed_types[${currentCount}][morning]`;
            eveningInput.name = `feed_types[${currentCount}][evening]`;

            feedIdInput.addEventListener('change', (e) => updateUnitDisplay(e.target));
            morningInput.addEventListener('input', () => updateTotal(clone));
            eveningInput.addEventListener('input', () => updateTotal(clone));

            document.getElementById('feed-items-list').appendChild(clone);

            updateUnitDisplay(feedIdInput);
            updateTotal(clone);

            document.getElementById('feed-error').classList.add('hidden');

            clone.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function removeFeedItem(button) {
            const itemDiv = button.closest('.p-5');
            itemDiv.remove();

            if (document.getElementById('feed-items-list').childElementCount === 0) {
                document.getElementById('feed-error').classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('feed-items-list').childElementCount === 0) {
                document.getElementById('feed-error').classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
