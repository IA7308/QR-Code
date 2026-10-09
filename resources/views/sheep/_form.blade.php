{{--
    PARTIAL FORM DOMBA - REDESIGNED
    Digunakan oleh create.blade.php dan edit.blade.php
--}}

<div class="space-y-6">

    {{-- (1) SECTION: INFORMASI IDENTITAS --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Informasi Identitas</h3>
                <p class="text-xs text-green-600">Data dasar & pengenalan domba</p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Eartag --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Eartag <span class="text-red-500">*</span>
                    </label>
                    <div class="flex shadow-sm rounded-xl">
                        <span class="inline-flex items-center px-4 text-sm font-bold text-gray-600 border-2 border-r-0 border-gray-200 bg-gradient-to-r from-gray-50 to-green-50 rounded-l-xl">
                            JAS-
                        </span>
                        <input
                            id="tag_number"
                            type="text"
                            name="tag_number"
                            value="{{ old('tag_number', $tagSuffix ?? '') }}"
                            required
                            autofocus
                            placeholder="001"
                            class="flex-1 block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-r-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('tag_number')" class="mt-2" />
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="category"
                        type="text"
                        name="category"
                        value="{{ old('category', $sheep->category ?? '') }}"
                        required
                        placeholder="Contoh: Pedaging"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                    />
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>

                {{-- Tipe/Ras --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Tipe/Ras <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="type"
                        type="text"
                        name="type"
                        value="{{ old('type', $sheep->type ?? '') }}"
                        required
                        placeholder="Contoh: Garut"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                    />
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>
            </div>
        </div>
    </div>

    {{-- (2) SECTION: LOKASI & KEMITRAAN --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl"
         x-data="{ status: '{{ $isPartner ? 'Internal' : old('placement_status', $sheep->placement_status ?? 'Internal') }}' }">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-blue-500 to-indigo-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Lokasi & Penempatan</h3>
                <p class="text-xs text-blue-600">Atur lokasi kandang atau kemitraan</p>
            </div>
        </div>

        <div class="p-6 space-y-6">
            {{-- Pilihan Status (HANYA ADMIN) --}}
            @if(!$isPartner)
                <div>
                    <label class="flex items-center gap-2 mb-3 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Status Penempatan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <label class="relative flex items-center p-4 transition-all duration-200 border-2 border-gray-200 cursor-pointer group rounded-xl hover:border-green-300 hover:bg-green-50"
                               :class="status === 'Internal' ? 'border-green-500 bg-green-50 shadow-md' : ''">
                            <input type="radio" name="placement_status" value="Internal" x-model="status" class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">🏠 Internal</span>
                                <span class="text-xs text-gray-500">Kandang Sendiri</span>
                            </div>
                            <div class="absolute top-2 right-2" x-show="status === 'Internal'">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 transition-all duration-200 border-2 border-gray-200 cursor-pointer group rounded-xl hover:border-blue-300 hover:bg-blue-50"
                               :class="status === 'Partner' ? 'border-blue-500 bg-blue-50 shadow-md' : ''">
                            <input type="radio" name="placement_status" value="Partner" x-model="status" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">🤝 Mitra</span>
                                <span class="text-xs text-gray-500">Titip Ternak / Gaduh</span>
                            </div>
                            <div class="absolute top-2 right-2" x-show="status === 'Partner'">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('placement_status')" class="mt-2" />
                </div>
            @else
                {{-- UNTUK MITRA: Otomatis Internal --}}
                <input type="hidden" name="placement_status" value="Internal">
            @endif

            {{-- Dropdown Kandang --}}
            <div x-show="status === 'Internal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Pilih Kandang <span class="text-red-500">*</span>
                </label>
                <select
                    id="shelter_id"
                    name="shelter_id"
                    onchange="if(typeof checkCapacity === 'function') checkCapacity(this.value)"
                    class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                >
                    <option value="">-- Pilih Kandang --</option>
                    @foreach($shelters as $shelter)
                        <option value="{{ $shelter->id }}" {{ old('shelter_id', $sheep->shelter_id ?? '') == $shelter->id ? 'selected' : '' }}>
                            {{ $shelter->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Capacity Info --}}
                <div id="capacity-info" class="hidden p-3 mt-3 border-2 border-green-200 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="capacity-text" class="text-sm font-semibold text-gray-700"></span>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('shelter_id')" class="mt-2" />
            </div>

            {{-- Dropdown Mitra (HANYA ADMIN) --}}
            @if(!$isPartner)
                <div x-show="status === 'Partner'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Pilih Mitra <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="partner_id"
                        name="partner_id"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none"
                    >
                        <option value="">-- Pilih Nama Mitra --</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ old('partner_id', $sheep->partner_id ?? '') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="flex items-center gap-1 mt-2 text-xs text-blue-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Domba akan tercatat di lokasi mitra ini
                    </p>
                    <x-input-error :messages="$errors->get('partner_id')" class="mt-2" />
                </div>
            @endif
        </div>
    </div>

    {{-- (3) SECTION: INFORMASI FISIK --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-purple-500 to-pink-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Informasi Fisik</h3>
                <p class="text-xs text-purple-600">Data biologis & kelahiran</p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Jenis Kelamin --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="gender"
                        name="gender"
                        required
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none"
                    >
                        <option value="Jantan" {{ old('gender', $sheep->gender ?? '') == 'Jantan' ? 'selected' : '' }}>♂️ Jantan</option>
                        <option value="Betina" {{ old('gender', $sheep->gender ?? '') == 'Betina' ? 'selected' : '' }}>♀️ Betina</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="date_of_birth"
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth', isset($sheep->date_of_birth) ? $sheep->date_of_birth->format('Y-m-d') : '') }}"
                        required
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none"
                    />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                </div>

                {{-- Bobot Lahir --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                        Bobot Lahir (KG)
                    </label>
                    <input
                        id="birth_weight"
                        type="number"
                        step="0.01"
                        name="birth_weight"
                        value="{{ old('birth_weight', $sheep->birth_weight ?? '') }}"
                        placeholder="0.00"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 focus:outline-none"
                    />
                    <x-input-error :messages="$errors->get('birth_weight')" class="mt-2" />
                </div>
            </div>
        </div>
    </div>

    {{-- (4) SECTION: SILSILAH/PEDIGREE --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-amber-50 to-yellow-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-amber-500 to-yellow-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Silsilah</h3>
                <p class="text-xs text-amber-600">Informasi induk & keturunan (opsional)</p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Induk Betina --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Induk Betina (Dam)
                    </label>
                    <select
                        id="mother_id"
                        name="mother_id"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 focus:outline-none"
                    >
                        <option value="">Pilih Induk (Jika diketahui)</option>
                        @foreach($potential_dams as $dam)
                            <option value="{{ $dam->id }}" {{ old('mother_id', $sheep->mother_id ?? '') == $dam->id ? 'selected' : '' }}>
                                Eartag: {{ $dam->tag_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Induk Pejantan --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Induk Pejantan (Sire)
                    </label>
                    <select
                        id="father_id"
                        name="father_id"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 focus:outline-none"
                    >
                        <option value="">Pilih Pejantan (Jika diketahui)</option>
                        @foreach($potential_sires as $sire)
                            <option value="{{ $sire->id }}" {{ old('father_id', $sheep->father_id ?? '') == $sire->id ? 'selected' : '' }}>
                                Eartag: {{ $sire->tag_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- (5) SECTION: HARGA & STATUS UNGGUL --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Informasi Harga & Kualitas</h3>
                <p class="text-xs text-green-600">Nilai investasi & status bibit</p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Harga Beli --}}
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Harga Beli (Rp)
                    </label>
                    <input
                        id="purchase_price"
                        type="number"
                        name="purchase_price"
                        value="{{ old('purchase_price', $sheep->purchase_price ?? '') }}"
                        placeholder="0"
                        class="block w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                    />
                </div>

                {{-- Bibit Unggul --}}
                <div class="flex items-center">
                    <div class="p-4 transition-all duration-200 border-2 border-gray-200 rounded-xl hover:border-amber-300 hover:bg-amber-50">
                        <input type="hidden" name="is_pedigree" value="0">
                        <label class="flex items-center cursor-pointer">
                            <input
                                id="is_pedigree"
                                name="is_pedigree"
                                type="checkbox"
                                value="1"
                                {{ (old('is_pedigree', $sheep->is_pedigree ?? 0) == 1) ? 'checked' : '' }}
                                class="w-5 h-5 border-gray-300 rounded text-amber-600 focus:ring-amber-500 focus:ring-4"
                            >
                            <div class="ml-3">
                                <span class="flex items-center gap-2 text-sm font-bold text-gray-900">
                                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                    Bibit Unggul
                                </span>
                                <span class="text-xs text-gray-500">Domba berkualitas premium</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- (6) SECTION: FOTO DOMBA --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-cyan-50 to-blue-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-cyan-500 to-blue-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Foto Domba</h3>
                <p class="text-xs text-cyan-600">Upload gambar untuk identifikasi visual</p>
            </div>
        </div>

        <div class="p-6">
            @if(isset($sheep) && $sheep->photo_path)
                <div class="p-4 mb-4 border-2 border-gray-200 rounded-xl bg-gray-50">
                    <p class="mb-3 text-sm font-bold text-gray-700">Foto Saat Ini:</p>
                    <div class="relative inline-block">
                        <img
                            src="{{ asset('storage/' . $sheep->photo_path) }}"
                            alt="Foto domba"
                            class="object-cover w-48 h-48 transition-transform duration-300 border-4 border-white shadow-lg rounded-2xl hover:scale-105"
                        >
                        <div class="absolute px-2 py-1 text-xs font-bold text-white bg-green-500 rounded-lg shadow-md bottom-2 right-2">
                            <svg class="inline w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Tersimpan
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">💡 Upload file baru untuk mengganti foto ini</p>
                </div>
            @endif

            <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ isset($sheep) && $sheep->photo_path ? 'Ganti Foto' : 'Upload Foto' }}
            </label>
            <input
                id="photo"
                type="file"
                name="photo"
                accept="image/*"
                class="block w-full px-4 py-2.5 text-sm text-gray-700 transition-all border-2 border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-gradient-to-r file:from-cyan-500 file:to-blue-600 file:text-white hover:file:from-cyan-600 hover:file:to-blue-700 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
            />
            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
            <p class="flex items-center gap-1 mt-2 text-xs text-gray-500">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Format: JPG, PNG, JPEG • Maksimal 2MB
            </p>
        </div>
    </div>

    {{-- (7) SECTION: CATATAN TAMBAHAN --}}
    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-slate-50">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-gray-500 to-slate-600">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Catatan Tambahan</h3>
                <p class="text-xs text-gray-600">Informasi pelengkap & observasi</p>
            </div>
        </div>

        <div class="p-6">
            <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-700">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                Deskripsi/Catatan
            </label>
            <textarea
                id="description"
                name="description"
                rows="5"
                placeholder="Contoh: Domba ini memiliki karakteristik fisik yang kuat, aktif, dan memiliki nafsu makan yang baik..."
                class="block w-full px-4 py-3 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 resize-none rounded-xl focus:border-gray-500 focus:ring-4 focus:ring-gray-100 focus:outline-none"
            >{{ old('description', $sheep->description ?? '') }}</textarea>
            <p class="flex items-center gap-1 mt-2 text-xs text-gray-500">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tambahkan informasi khusus atau ciri khas domba
            </p>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="flex items-center justify-end gap-4 p-6 bg-white border-t-2 border-gray-100 shadow-lg rounded-2xl">
        <a href="{{ route('sheep.index') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-gray-700 transition-all duration-200 bg-gray-100 border-2 border-gray-200 rounded-xl hover:bg-gray-200 hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Batal
        </a>

        <button type="submit" class="flex items-center gap-2 px-8 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ isset($sheep) ? 'Simpan Perubahan' : 'Simpan Data Domba' }}
        </button>
    </div>

</div>

{{-- JAVASCRIPT: Capacity Check & Alpine.js Init --}}
<script>
    function checkCapacity(shelterId) {
        const infoDiv = document.getElementById('capacity-info');
        const infoText = document.getElementById('capacity-text');

        if (!shelterId) {
            infoDiv.classList.add('hidden');
            return;
        }

        infoDiv.classList.remove('hidden');
        infoText.innerHTML = '<span class="text-gray-500">⏳ Memuat data kapasitas...</span>';

        fetch(`/shelters/${shelterId}/capacity`)
            .then(response => response.json())
            .then(data => {
                const isFull = data.remaining <= 0;
                const isAlmostFull = data.remaining > 0 && data.remaining <= 3;

                let statusBadge = '';
                if (isFull) {
                    statusBadge = '<span class="inline-flex items-center gap-1 px-2 py-1 ml-2 text-xs font-bold text-red-700 border-2 border-red-200 rounded-lg bg-red-50 animate-pulse">⚠️ PENUH!</span>';
                } else if (isAlmostFull) {
                    statusBadge = '<span class="inline-flex items-center gap-1 px-2 py-1 ml-2 text-xs font-bold text-yellow-700 border-2 border-yellow-200 rounded-lg bg-yellow-50">⚡ Hampir Penuh</span>';
                } else {
                    statusBadge = '<span class="inline-flex items-center gap-1 px-2 py-1 ml-2 text-xs font-bold text-green-700 border-2 border-green-200 rounded-lg bg-green-50">✅ Tersedia</span>';
                }

                infoText.innerHTML = `
                    <span class="font-bold text-gray-900">Kapasitas Kandang:</span>
                    ${data.current}/${data.capacity} Ekor
                    <span class="font-bold ${isFull ? 'text-red-600' : 'text-green-600'}">(Sisa: ${data.remaining})</span>
                    ${statusBadge}
                `;
            })
            .catch(error => {
                infoText.innerHTML = '<span class="text-red-600">❌ Gagal memuat data kapasitas</span>';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const initialShelterId = document.getElementById('shelter_id').value;
        if (initialShelterId) {
            checkCapacity(initialShelterId);
        }
    });
</script>

<style>
    /* Smooth transitions untuk Alpine.js */
    [x-cloak] { display: none !important; }

    /* Custom file input styling enhancement */
    input[type="file"]::file-selector-button {
        transition: all 0.2s ease;
    }

    input[type="file"]:hover::file-selector-button {
        transform: scale(1.05);
    }
</style>
