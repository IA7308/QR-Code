<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-green-400 to-emerald-600">
                <span class="text-2xl">🐑</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-800">
                    Detail Domba
                </h2>
                <p class="text-sm font-medium text-green-600">Profil lengkap & riwayat domba</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="relative flex items-center gap-3 p-4 mb-6 text-green-800 border-l-4 border-green-500 rounded-lg shadow-sm bg-green-50">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="relative flex items-center gap-3 p-4 mb-6 text-red-800 border-l-4 border-red-500 rounded-lg shadow-sm bg-red-50">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- KOLOM KIRI: INFO UTAMA & REPRODUKSI (2/3 Lebar) --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- 1. KARTU INFO UTAMA & FOTO --}}
                    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                        {{-- LOGIKA STATUS --}}
                        @php
                            // 1. Status Kesehatan Aktif
                            $activeSickness = $sheep->healthRecords
                                ->sort(function ($a, $b) {
                                    if ($a->record_date == $b->record_date) {
                                        return $b->id - $a->id;
                                    }
                                    return strtotime($b->record_date) - strtotime($a->record_date);
                                })
                                ->first();

                            if ($activeSickness && in_array($activeSickness->status, ['Completed', 'Sembuh / Selesai'])) {
                                $activeSickness = null;
                            }

                            // 2. Status Kehamilan
                            $activePregnancy = null;
                            if ($sheep->gender === 'Betina') {
                                $activePregnancy = $sheep->asDamReproductionRecords
                                    ->where('status', 'Pregnant')
                                    ->first();
                            }
                        @endphp

                        <div class="p-6">
                            {{-- HEADER: Eartag + Badges --}}
                            <div class="flex items-start justify-between mb-6">
                                <div class="flex-1">
                                    <h3 class="mb-3 text-3xl font-black text-gray-900">{{ $sheep->tag_number }}</h3>

                                    <div class="flex flex-wrap gap-2">
                                        {{-- Badge Status Kemitraan --}}
                                        @if($sheep->placement_status === 'Partner')
                                            @if($sheep->partner_id)
                                                <span class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold text-blue-700 border-2 border-blue-200 rounded-lg bg-blue-50 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                    Mitra: {{ $sheep->partner->name ?? 'Unknown' }}
                                                </span>
                                            @else
                                                <span class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold text-yellow-700 border-2 border-yellow-200 rounded-lg bg-yellow-50 shadow-sm animate-pulse">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Menunggu Konfirmasi Mitra
                                                </span>
                                            @endif
                                        @endif

                                        @if($sheep->is_pedigree)
                                            <span class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold text-white rounded-lg shadow-md bg-gradient-to-r from-amber-400 to-yellow-500">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                Bibit Unggul
                                            </span>
                                        @endif

                                        @if($activeSickness)
                                            <span class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold text-white bg-red-500 rounded-lg shadow-md animate-pulse">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                {{ $activeSickness->status }}: {{ Str::limit($activeSickness->diagnosis, 15) }}
                                            </span>
                                        @endif

                                        @if($activePregnancy)
                                            <span class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold text-white bg-purple-500 rounded-lg shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                                HAMIL (HPL: {{ \Carbon\Carbon::parse($activePregnancy->expected_delivery_date)->diffForHumans() }})
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('sheep.print', $sheep) }}" target="_blank" class="flex items-center gap-1 px-4 py-2 text-sm font-bold text-white transition-all duration-200 bg-red-500 rounded-lg shadow-md hover:bg-red-600 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Cetak
                                    </a>

                                    <button onclick="openQrModal()" class="flex items-center gap-1 px-4 py-2 text-sm font-bold text-gray-700 transition-all duration-200 bg-gray-100 border-2 border-gray-200 rounded-lg shadow-md hover:bg-gray-200 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        QR
                                    </button>

                                    <a href="{{ route('sheep.edit', $sheep) }}" class="flex items-center gap-1 px-4 py-2 text-sm font-bold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </a>
                                </div>
                            </div>

                            {{-- CONTENT: Foto + Info --}}
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                {{-- Foto --}}
                                <div class="relative overflow-hidden group rounded-2xl">
                                    <img
                                        class="object-cover w-full transition-transform duration-500 shadow-lg h-80 group-hover:scale-110 rounded-2xl"
                                        src="{{ $sheep->photo_path ? asset('storage/' . $sheep->photo_path) : 'https://placehold.co/600x450/d1fae5/047857?text=' . urlencode($sheep->tag_number) }}"
                                        alt="Foto Domba {{ $sheep->tag_number }}"
                                        onerror="this.onerror=null; this.src='https://placehold.co/600x450/d1fae5/047857?text=Image+Not+Found';"
                                    >
                                    {{-- Gender Badge Overlay --}}
                                    <div class="absolute top-3 left-3">
                                        @if($sheep->gender == 'Jantan')
                                            <span class="flex items-center gap-1 px-3 py-1.5 text-sm font-bold text-blue-700 border-2 border-blue-200 rounded-lg bg-blue-50/90 backdrop-blur-sm shadow-lg">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5L13.6 10.4M19 5h-5M19 5v5"></path></svg>
                                                Jantan
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 px-3 py-1.5 text-sm font-bold text-pink-700 border-2 border-pink-200 rounded-lg bg-pink-50/90 backdrop-blur-sm shadow-lg">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7M9 18h6"></path></svg>
                                                Betina
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Info Detail --}}
                                <div class="space-y-4">
                                    {{-- Lokasi --}}
                                    <div class="flex items-start gap-3 p-3 transition-all duration-200 border-2 border-gray-100 rounded-xl hover:border-green-200 hover:bg-green-50">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Lokasi / Kandang</p>
                                            <p class="text-sm font-bold text-gray-900">
                                                @if($sheep->placement_status === 'Partner' && $sheep->partner_id)
                                                    <span class="text-green-700">{{ $sheep->partner->name }}</span>
                                                    @if($sheep->shelter)
                                                        <span class="text-gray-600">({{ $sheep->shelter->name }})</span>
                                                    @else
                                                        <span class="italic text-gray-400">(Belum masuk kandang)</span>
                                                    @endif
                                                @elseif($sheep->placement_status === 'Partner')
                                                    <span class="italic text-yellow-600">Proses penempatan ke Mitra</span>
                                                @else
                                                    {{ $sheep->shelter->name ?? 'Belum ada kandang' }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Umur --}}
                                    <div class="flex items-start gap-3 p-3 transition-all duration-200 border-2 border-gray-100 rounded-xl hover:border-green-200 hover:bg-green-50">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Umur / Tanggal Lahir</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $sheep->date_of_birth->diffForHumans(null, true) }}</p>
                                            <p class="text-xs text-gray-500">Lahir: {{ $sheep->date_of_birth->format('d M Y') }}</p>
                                        </div>
                                    </div>

                                    {{-- Bobot Lahir --}}
                                    <div class="flex items-start gap-3 p-3 transition-all duration-200 border-2 border-gray-100 rounded-xl hover:border-green-200 hover:bg-green-50">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Bobot Lahir</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $sheep->birth_weight ? $sheep->birth_weight . ' kg' : 'Tidak tercatat' }}</p>
                                        </div>
                                    </div>

                                    {{-- Kategori & Tipe --}}
                                    <div class="flex items-start gap-3 p-3 transition-all duration-200 border-2 border-gray-100 rounded-xl hover:border-green-200 hover:bg-green-50">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-amber-100 to-yellow-100">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Kategori / Ras</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $sheep->category }} / {{ $sheep->type }}</p>
                                        </div>
                                    </div>

                                    {{-- Harga Beli --}}
                                    <div class="flex items-start gap-3 p-3 transition-all duration-200 border-2 border-gray-100 rounded-xl hover:border-green-200 hover:bg-green-50">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-500 uppercase">Harga Beli</p>
                                            <p class="text-sm font-bold text-green-600">Rp {{ number_format($sheep->purchase_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    @if($sheep->description)
                                        <div class="p-3 border-2 border-gray-100 rounded-xl bg-gray-50">
                                            <p class="mb-1 text-xs font-semibold text-gray-500 uppercase">Catatan</p>
                                            <p class="text-sm italic text-gray-700">"{{ $sheep->description }}"</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. RIWAYAT REPRODUKSI (KHUSUS BETINA) --}}
                    @if($sheep->gender === 'Betina')
                        <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                            <div class="flex items-center justify-between p-5 border-b-2 border-purple-100 bg-gradient-to-r from-purple-50 to-pink-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 text-purple-600 bg-purple-200 rounded-lg shadow-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-purple-900">Riwayat Reproduksi</h3>
                                        <p class="text-xs text-purple-600">Data perkawinan & kelahiran</p>
                                    </div>
                                </div>
                                <a href="{{ route('sheep.reproduction-records.create', $sheep) }}">
                                    <button class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Catat Kawin
                                    </button>
                                </a>
                            </div>
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-purple-50">
                                            <tr>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Tgl Kawin</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Pejantan</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Keterangan Tanggal</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Status</th>
                                                <th class="px-4 py-3 text-xs font-bold text-right text-gray-700 uppercase">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @forelse($sheep->asDamReproductionRecords()->with('sire')->orderBy('mating_date', 'desc')->orderBy('id', 'desc')->get() as $repro)
                                                <tr class="transition-colors hover:bg-purple-50">
                                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($repro->mating_date)->format('d M Y') }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $repro->sire->tag_number ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        @if($repro->status == 'Delivered' && $repro->actual_delivery_date)
                                                            <span class="font-bold text-green-700">Lahir: {{ $repro->actual_delivery_date->format('d M Y') }}</span>
                                                            <br><span class="text-xs text-gray-500">({{ $repro->offspring_count }} anak)</span>
                                                        @elseif($repro->status == 'Pregnant')
                                                            HPL: {{ $repro->expected_delivery_date ? $repro->expected_delivery_date->format('d M Y') : '-' }}
                                                            <br><span class="text-xs font-bold text-purple-600">({{ $repro->expected_delivery_date ? $repro->expected_delivery_date->diffForHumans() : '' }})</span>
                                                        @else
                                                            HPL: {{ $repro->expected_delivery_date ? $repro->expected_delivery_date->format('d M Y') : '-' }}
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="px-3 py-1 text-xs font-bold border-2 rounded-lg {{ $repro->badge_style }}">
                                                            {{ $repro->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-right">
                                                        <div class="flex justify-end gap-2">
                                                            <a href="{{ route('reproduction-records.edit', $repro) }}" class="font-bold text-green-600 transition-colors hover:text-green-700">Update</a>
                                                            <form action="{{ route('reproduction-records.destroy', $repro) }}" method="POST" onsubmit="return confirm('Hapus data ini?');" class="inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="font-bold text-red-600 transition-colors hover:text-red-700">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-4 py-8 text-center">
                                                        <div class="flex flex-col items-center">
                                                            <div class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-purple-100 to-pink-100">
                                                                <span class="text-3xl">💝</span>
                                                            </div>
                                                            <p class="text-sm font-semibold text-gray-700">Belum Ada Riwayat</p>
                                                            <p class="text-xs text-gray-500">Belum ada data reproduksi tercatat</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- 3. RIWAYAT TIMBANGAN --}}
                    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                        <div class="flex items-center justify-between p-5 border-b-2 border-green-100 bg-gradient-to-r from-green-50 to-emerald-50">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Riwayat Pertumbuhan</h3>
                                    <p class="text-xs text-green-600">Monitoring berat badan domba</p>
                                </div>
                            </div>
                            <a href="{{ route('sheep.weights.create', $sheep) }}">
                                <button class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Timbang
                                </button>
                            </a>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Berat (KG)</th>
                                            <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Catatan</th>
                                            <th class="px-4 py-3 text-xs font-bold text-right text-gray-700 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($weightRecords as $record)
                                            <tr class="transition-colors hover:bg-green-50">
                                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($record->weighing_date)->format('d M Y') }}</td>
                                                <td class="px-4 py-3 text-base font-black text-green-600">{{ $record->weight }} kg</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $record->notes ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-right">
                                                    <form action="{{ route('weights.destroy', $record) }}" method="POST" onsubmit="return confirm('Hapus data timbangan ini?');" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="font-bold text-red-600 transition-colors hover:text-red-700">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-4 py-8 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <div class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-green-100 to-emerald-100">
                                                            <span class="text-3xl">⚖️</span>
                                                        </div>
                                                        <p class="text-sm font-semibold text-gray-700">Belum Ada Data</p>
                                                        <p class="text-xs text-gray-500">Belum ada data timbangan tercatat</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">{{ $weightRecords->links() }}</div>
                        </div>
                    </div>

                    {{-- 4. SILSILAH & KELUARGA --}}
                    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                        <div class="p-6 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-blue-500 to-indigo-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Silsilah & Keluarga</h3>
                                    <p class="text-xs text-blue-600">Pohon keluarga & keturunan</p>
                                </div>
                            </div>
                        </div>

                        @php
                            $children = $sheep->gender === 'Jantan' ? $sheep->offspringAsFather : $sheep->offspringAsMother;
                            $siblings = collect();
                            if ($sheep->father_id || $sheep->mother_id) {
                                $siblings = \App\Models\Sheep::where('id', '!=', $sheep->id)
                                    ->where(function($query) use ($sheep) {
                                        if ($sheep->father_id) $query->orWhere('father_id', $sheep->father_id);
                                        if ($sheep->mother_id) $query->orWhere('mother_id', $sheep->mother_id);
                                    })
                                    ->get();
                            }
                        @endphp

                        <div class="p-6 space-y-6">
                            {{-- ORANG TUA --}}
                            <div>
                                <h4 class="flex items-center gap-2 pb-2 mb-3 text-base font-bold text-gray-800 border-b-2 border-purple-200">
                                    <span class="text-xl">👪</span>
                                    Orang Tua
                                </h4>
                                <div class="overflow-x-auto border-2 border-gray-100 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-purple-50">
                                            <tr>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Peran</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Eartag</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Gender</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Tanggal Lahir</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Ibu</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Bapak</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr class="transition-colors hover:bg-purple-50">
                                                <td class="px-4 py-3 text-sm font-bold text-pink-700">Ibu (Dam)</td>
                                                <td class="px-4 py-3 text-sm font-bold text-green-600">
                                                    @if($sheep->mother) <a href="{{ route('sheep.show', $sheep->mother) }}" class="hover:underline">{{ $sheep->mother->tag_number }}</a> @else - @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">Betina</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $sheep->mother ? $sheep->mother->date_of_birth->format('d M Y') : '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $sheep->mother?->mother?->tag_number ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $sheep->mother?->father?->tag_number ?? '-' }}</td>
                                            </tr>
                                            <tr class="transition-colors hover:bg-blue-50">
                                                <td class="px-4 py-3 text-sm font-bold text-blue-700">Bapak (Sire)</td>
                                                <td class="px-4 py-3 text-sm font-bold text-green-600">
                                                    @if($sheep->father) <a href="{{ route('sheep.show', $sheep->father) }}" class="hover:underline">{{ $sheep->father->tag_number }}</a> @else - @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">Jantan</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $sheep->father ? $sheep->father->date_of_birth->format('d M Y') : '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $sheep->father?->mother?->tag_number ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">{{ $sheep->father?->father?->tag_number ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- SAUDARA --}}
                            <div>
                                <h4 class="flex items-center gap-2 pb-2 mb-3 text-base font-bold text-gray-800 border-b-2 border-yellow-200">
                                    <span class="text-xl">👫</span>
                                    Saudara Kandung
                                </h4>
                                <div class="overflow-x-auto border-2 border-gray-100 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-yellow-50">
                                            <tr>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Eartag</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Gender</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Tanggal Lahir</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Ibu</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Bapak</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($siblings as $sib)
                                                <tr class="transition-colors hover:bg-yellow-50">
                                                    <td class="px-4 py-3 text-sm font-bold text-green-600"><a href="{{ route('sheep.show', $sib) }}" class="hover:underline">{{ $sib->tag_number }}</a></td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $sib->gender }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $sib->date_of_birth->format('d M Y') }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $sib->mother?->tag_number ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $sib->father?->tag_number ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-4 py-6 text-center">
                                                        <p class="text-sm italic text-gray-500">Tidak ada data saudara kandung</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- ANAK --}}
                            <div>
                                <h4 class="flex items-center gap-2 pb-2 mb-3 text-base font-bold text-gray-800 border-b-2 border-green-200">
                                    <span class="text-xl">👶</span>
                                    Keturunan (Offspring)
                                </h4>
                                <div class="overflow-x-auto border-2 border-gray-100 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-green-50">
                                            <tr>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Eartag</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Gender</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Tanggal Lahir</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Ibu</th>
                                                <th class="px-4 py-3 text-xs font-bold text-left text-gray-700 uppercase">Bapak</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($children as $child)
                                                <tr class="transition-colors hover:bg-green-50">
                                                    <td class="px-4 py-3 text-sm font-bold text-green-600"><a href="{{ route('sheep.show', $child) }}" class="hover:underline">{{ $child->tag_number }}</a></td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $child->gender }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $child->date_of_birth->format('d M Y') }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $child->mother?->tag_number ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $child->father?->tag_number ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-4 py-6 text-center">
                                                        <p class="text-sm italic text-gray-500">Belum ada data keturunan</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: SIDEBAR --}}
                <div class="space-y-6 lg:col-span-1">

                    {{-- KARTU REKOMENDASI HARGA --}}
                    <div class="overflow-hidden transition-shadow shadow-lg hover:shadow-2xl rounded-2xl">
                        <div class="p-6 bg-gradient-to-br from-green-50 via-emerald-50 to-green-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-green-500 to-emerald-600">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Analisis Harga Jual</h3>
                                    <p class="text-xs text-green-700">Estimasi untung rugi</p>
                                </div>
                            </div>

                            <div class="p-4 mb-4 border-2 border-green-200 rounded-xl bg-white/80 backdrop-blur-sm">
                                <span class="block text-xs font-bold text-gray-600 uppercase">Rekomendasi Harga</span>
                                <span class="block text-4xl font-black text-green-600">
                                    Rp {{ number_format($priceData['rekomendasi'], 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between p-3 border-2 border-green-100 rounded-xl bg-white/60">
                                    <span class="font-semibold text-gray-700">Nilai Daging ({{ $priceData['berat_kg'] }}kg)</span>
                                    <span class="font-bold text-green-600">Rp {{ number_format($priceData['nilai_daging'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 border-2 border-red-100 rounded-xl bg-white/60">
                                    <span class="font-semibold text-gray-700">Total Modal</span>
                                    <span class="font-bold text-red-500">Rp {{ number_format($priceData['total_modal'], 0, ',', '.') }}</span>
                                </div>
                                @if($priceData['bonus'] > 0)
                                    <div class="flex items-center justify-between p-3 border-2 border-amber-100 rounded-xl bg-white/60">
                                        <span class="font-semibold text-gray-700">Bonus Unggul</span>
                                        <span class="font-bold text-amber-600">+ Rp {{ number_format($priceData['bonus'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($priceData['penalti'] > 0)
                                    <div class="flex items-center justify-between p-3 border-2 border-red-100 rounded-xl bg-white/60">
                                        <span class="font-semibold text-gray-700">Penalti Sakit</span>
                                        <span class="font-bold text-red-600">- Rp {{ number_format($priceData['penalti'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- STATUS KESEHATAN --}}
                    <div class="overflow-hidden transition-shadow shadow-lg hover:shadow-2xl rounded-2xl">
                        <div class="p-5 border-b-2 border-red-100 bg-gradient-to-r from-red-50 to-pink-50">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 text-red-600 bg-red-200 rounded-lg shadow-md">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Status Kesehatan</h3>
                                    <p class="text-xs text-red-600">Riwayat medical records</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <a href="{{ route('sheep.health-records.create', $sheep) }}" class="flex items-center justify-center w-full gap-2 px-4 py-3 mb-5 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Lapor Sakit / Penanganan
                            </a>

                            <div class="space-y-3">
                                @forelse($healthRecords as $health)
                                    <div class="p-4 transition-all duration-200 border-2 border-gray-200 hover:shadow-md rounded-xl {{ $health->card_style }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <span class="text-xs font-semibold text-gray-500">{{ \Carbon\Carbon::parse($health->record_date)->format('d M Y') }}</span>
                                            <span class="px-2 py-1 text-xs font-bold border-2 rounded-lg {{ $health->badge_style }}">
                                                {{ $health->status }}
                                            </span>
                                        </div>
                                        <h4 class="mb-2 text-sm font-bold text-gray-900">{{ $health->diagnosis }}</h4>
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            @foreach($health->symptoms as $sym)
                                                <span class="px-2 py-0.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded">{{ $sym->name }}</span>
                                            @endforeach
                                        </div>
                                        <p class="mb-3 text-xs italic text-gray-600">
                                            "{{ Str::limit($health->treatment_details, 80) }}"
                                        </p>
                                        <div class="flex justify-end">
                                            <form action="{{ route('health-records.destroy', $health) }}" method="POST" onsubmit="return confirm('Hapus riwayat sakit ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-red-600 transition-colors hover:text-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center justify-center py-12 text-center">
                                        <div class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-green-100 to-emerald-100">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700">Alhamdulillah! 🎉</p>
                                        <p class="text-xs text-gray-500">Tidak ada riwayat penyakit. Domba sehat!</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="mt-4">{{ $healthRecords->links() }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div id="qrModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-md p-8 m-4 text-center transition-all transform bg-white shadow-2xl rounded-3xl animate-fade-in">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full shadow-lg bg-gradient-to-br from-green-400 to-emerald-600">
                <span class="text-3xl">🐑</span>
            </div>

            <h3 class="mb-2 text-2xl font-black text-gray-900">QR Code Domba</h3>
            <p class="mb-1 text-lg font-bold text-green-600">{{ $sheep->tag_number }}</p>
            <p class="mb-6 text-sm text-gray-500">Scan untuk membuka profil domba ini</p>

            <div id="qr-container" class="flex justify-center p-6 mb-6 border-2 border-gray-200 rounded-2xl bg-gradient-to-br from-gray-50 to-green-50">
                {{-- Generate QR Code (SVG) --}}
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('sheep.show', $sheep)) !!}
            </div>

            <div class="flex gap-3">
                <button onclick="downloadQrImage()" class="flex items-center justify-center flex-1 gap-2 px-5 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download PNG
                </button>

                <button onclick="closeQrModal()" class="flex items-center justify-center flex-1 gap-2 px-5 py-3 text-sm font-bold text-gray-700 transition-all duration-200 bg-gray-100 border-2 border-gray-200 rounded-xl hover:bg-gray-200 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT: MODAL & DOWNLOAD QR --}}
    <script>
        function openQrModal() {
            document.getElementById('qrModal').classList.remove('hidden');
        }
        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        function downloadQrImage() {
            // Ambil elemen SVG dari dalam container
            const svg = document.querySelector('#qr-container svg');

            if (!svg) {
                alert('QR Code belum dimuat.');
                return;
            }

            // Buat Canvas untuk konversi
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            // Serialisasi SVG ke String XML
            const data = (new XMLSerializer()).serializeToString(svg);

            // Buat Blob dari data SVG
            const svgBlob = new Blob([data], {type: 'image/svg+xml;charset=utf-8'});
            const url = URL.createObjectURL(svgBlob);

            img.onload = function() {
                // Canvas size: 250x250 for QR + extra space for text
                const qrSize = 250;
                const textHeight = 60;
                const padding = 20;
                canvas.width = qrSize + (padding * 2);
                canvas.height = qrSize + textHeight + (padding * 2);

                // Fill background white
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Draw QR Code (centered with padding)
                ctx.drawImage(img, padding, padding, qrSize, qrSize);

                // Add Text (Eartag) - centered below QR
                ctx.font = "bold 24px Arial";
                ctx.fillStyle = "#059669"; // Green color
                ctx.textAlign = "center";
                ctx.fillText("{{ $sheep->tag_number }}", canvas.width / 2, qrSize + padding + 35);

                // Add subtitle
                ctx.font = "14px Arial";
                ctx.fillStyle = "#6B7280";
                ctx.fillText("JAS Farm - Scan Me!", canvas.width / 2, qrSize + padding + 55);

                // Download link
                const a = document.createElement('a');
                a.download = 'QR_Domba_{{ $sheep->tag_number }}.png';
                a.href = canvas.toDataURL('image/png');
                a.click();

                URL.revokeObjectURL(url);
            };

            img.src = url;
        }

        // Close modal when clicking outside
        document.getElementById('qrModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQrModal();
            }
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</x-app-layout>
