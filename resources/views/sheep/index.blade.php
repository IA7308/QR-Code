<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-600">
                    <span class="text-2xl">🐑</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Daftar Domba') }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 mt-1 text-sm font-bold text-green-700 border-2 border-green-200 rounded-full bg-green-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $sheep->total() }} Ekor Terdaftar
                    </span>
                </div>
            </div>
            <a href="{{ route('sheep.create') }}">
                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('Tambah Domba') }}
                </button>
            </a>
        </div>
    </x-slot>

    {{-- INJECT SERVICE HARGA --}}
    @inject('priceService', 'App\Services\PriceRecommenderService')

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="relative flex items-center gap-3 p-4 mb-6 text-green-800 border-l-4 border-green-500 rounded-lg shadow-sm bg-green-50">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- === FILTER & SEARCH BAR (MODERN COMPACT) === --}}
            <div class="p-6 mb-8 transition-shadow bg-white shadow-md rounded-2xl hover:shadow-lg">
                <form id="filter-form" method="GET" action="{{ route('sheep.index') }}">
                    <div class="space-y-4">
                        {{-- Search Bar --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Cari berdasarkan nomor eartag... (contoh: JAS-001)"
                                class="w-full py-3 pl-12 pr-4 text-base font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-full focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                            >
                        </div>

                        {{-- Filter Row --}}
                        <div class="grid items-center grid-cols-1 gap-3 md:grid-cols-3">
                            {{-- Filter Gender --}}
                            <div class="relative">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    Gender
                                </label>
                                <select name="gender" id="gender" class="w-full py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 transition-all bg-white border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none">
                                    <option value="">🐏 Semua Gender</option>
                                    <option value="Jantan" {{ request('gender') == 'Jantan' ? 'selected' : '' }}>♂️ Jantan</option>
                                    <option value="Betina" {{ request('gender') == 'Betina' ? 'selected' : '' }}>♀️ Betina</option>
                                </select>
                            </div>

                            {{-- Filter Kandang --}}
                            <div class="relative">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    Lokasi Kandang
                                </label>
                                <select name="shelter_id" id="shelter_id" class="w-full py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 transition-all bg-white border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none">
                                    <option value="">🏠 Semua Kandang</option>
                                    @foreach($shelters as $shelter)
                                        <option value="{{ $shelter->id }}" {{ request('shelter_id') == $shelter->id ? 'selected' : '' }}>
                                            {{ $shelter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2 md:mt-7">
                                <button type="submit" class="flex items-center justify-center flex-1 gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl shadow-md bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Cari
                                </button>
                                @if(request()->hasAny(['search', 'gender', 'shelter_id']))
                                    <a href="{{ route('sheep.index') }}" class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-700 transition-all duration-200 bg-gray-100 rounded-xl hover:bg-gray-200 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- === GRID KARTU DOMBA === --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                @forelse ($sheep as $s)
                    @php
                        // 1. LOGIKA KESEHATAN
                        $latestRecord = $s->healthRecords
                            ->sort(function ($a, $b) {
                                $dateA = \Carbon\Carbon::parse($a->record_date)->timestamp;
                                $dateB = \Carbon\Carbon::parse($b->record_date)->timestamp;
                                if ($dateA === $dateB) { return $b->id - $a->id; }
                                return $dateB - $dateA;
                            })->first();

                        $activeSickness = ($latestRecord && !in_array($latestRecord->status, ['Completed', 'Sembuh / Selesai'])) ? $latestRecord : null;

                        // 2. LOGIKA KEHAMILAN
                        $activePregnancy = null;
                        if ($s->gender === 'Betina') {
                            $activePregnancy = $s->asDamReproductionRecords->where('status', 'Pregnant')->first();
                        }

                        // 3. LOGIKA HARGA & BERAT
                        $analysis = $priceService->calculate($s);
                        $latestWeight = $analysis['berat_kg'];
                        $priceRecommendation = $analysis['rekomendasi'];

                        // 4. BORDER CARD
                        $cardBorderClass = 'border-2 border-gray-100';
                        if ($activeSickness) {
                            $cardBorderClass = 'border-2 border-red-300 shadow-lg shadow-red-100';
                        } elseif ($activePregnancy) {
                            $cardBorderClass = 'border-2 border-purple-300 shadow-lg shadow-purple-100';
                        }

                        // 5. STATUS REQUEST PENEMPATAN
                        $isRejected = false;
                        $rejectionNote = '';
                        if ($s->placement_status === 'Partner' && !$s->partner_id && $s->latestPlacementRequest) {
                            if ($s->latestPlacementRequest->status == 'rejected') {
                                $isRejected = true;
                                $rejectionNote = $s->latestPlacementRequest->notes;
                            }
                        }
                    @endphp

                    {{-- CARD CONTAINER --}}
                    <div class="flex flex-col h-full overflow-hidden transition-all duration-300 transform bg-white shadow-md hover:scale-105 hover:shadow-2xl rounded-2xl {{ $cardBorderClass }}">

                        {{-- LINK UTAMA (Gambar & Info) --}}
                        <div class="relative block group">
                            <a href="{{ route('sheep.show', $s) }}" class="block">
                                <div class="relative overflow-hidden">
                                    <img
                                        class="object-cover w-full h-56 transition-transform duration-500 group-hover:scale-110 {{ $isRejected ? 'opacity-40 grayscale' : '' }}"
                                        src="{{ $s->photo_path ? asset('storage/' . $s->photo_path) : 'https://placehold.co/600x400/d1fae5/047857?text=' . urlencode($s->tag_number) }}"
                                        alt="Foto {{ $s->tag_number }}"
                                        onerror="this.onerror=null; this.src='https://placehold.co/600x400/d1fae5/047857?text=No+Image';"
                                    >

                                    {{-- OVERLAY JIKA DITOLAK --}}
                                    @if($isRejected)
                                        <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center bg-black/70">
                                            <div class="mb-3 text-white">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="text-base font-bold text-red-400">PERMINTAAN DITOLAK</span>
                                                <p class="mt-2 text-sm italic text-gray-300 line-clamp-2">"{{ $rejectionNote }}"</p>
                                            </div>

                                            <div class="flex w-full gap-2 mt-3">
                                                <a href="{{ route('sheep.edit', $s) }}" class="flex items-center justify-center flex-1 gap-1 px-3 py-2 text-sm font-bold text-white transition-all duration-200 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    Coba Lagi
                                                </a>

                                                <form action="{{ route('sheep.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus data domba {{ $s->tag_number }} permanen?');" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center justify-center w-full gap-1 px-3 py-2 text-sm font-bold text-white transition-all duration-200 bg-red-600 rounded-lg hover:bg-red-700">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- BADGES (Pojok Kanan Atas) - DIPERBESAR --}}
                                    @if(!$isRejected)
                                        <div class="absolute flex flex-col items-end gap-2 top-3 right-3">
                                            @if($s->placement_status === 'Partner')
                                                @if($s->partner_id)
                                                    <span class="flex items-center gap-1.5 bg-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                        MITRA
                                                    </span>
                                                @else
                                                    <span class="flex items-center gap-1.5 bg-yellow-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg animate-pulse">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        PENDING
                                                    </span>
                                                @endif
                                            @endif

                                            @if($s->is_pedigree)
                                                <span class="flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-yellow-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                    UNGGUL
                                                </span>
                                            @endif

                                            @if($activeSickness)
                                                <span class="flex items-center gap-1.5 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    SAKIT
                                                </span>
                                            @endif

                                            @if($activePregnancy)
                                                <span class="flex items-center gap-1.5 bg-purple-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                                    HAMIL
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- BADGE BERAT & HARGA --}}
                                    @if(!$isRejected)
                                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                                            <div class="flex items-end justify-between text-white">
                                                <div>
                                                    <p class="text-xs font-medium opacity-90">Berat Terakhir</p>
                                                    <p class="text-2xl font-bold leading-none">{{ $latestWeight }} <span class="text-sm font-normal">kg</span></p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs font-medium opacity-90">Estimasi Harga</p>
                                                    <p class="text-base font-bold leading-none text-green-400">Rp {{ number_format($priceRecommendation, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5 {{ $isRejected ? 'opacity-50' : '' }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $s->tag_number }}</h3>

                                        {{-- GENDER ICON - DIPERBESAR --}}
                                        @if($s->gender == 'Jantan')
                                            <span class="flex items-center px-3 py-1 text-sm font-bold text-blue-700 border-2 border-blue-200 rounded-full bg-blue-50">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5L13.6 10.4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5h-5"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5v5"></path></svg>
                                                Jantan
                                            </span>
                                        @else
                                            <span class="flex items-center px-3 py-1 text-sm font-bold text-pink-700 border-2 border-pink-200 rounded-full bg-pink-50">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18h6"></path></svg>
                                                Betina
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center mb-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        @if($s->placement_status === 'Partner' && $s->partner_id)
                                            Mitra: {{ $s->partner->name ?? 'Unknown' }}
                                        @elseif($s->placement_status === 'Partner')
                                            {{ $isRejected ? 'Ditolak Mitra' : 'Menunggu Mitra...' }}
                                        @else
                                            {{ $s->shelter->name ?? 'Belum ada kandang' }}
                                        @endif
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $s->date_of_birth->diffForHumans(null, true) }}
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- FOOTER KARTU: QUICK ACTIONS --}}
                        @if(!$isRejected)
                            <div class="flex items-stretch border-t-2 border-gray-100 divide-x-2 divide-gray-100 bg-gray-50">
                                <a href="{{ route('sheep.weights.create', $s) }}" class="flex items-center justify-center flex-1 py-3 text-sm font-bold text-gray-700 transition-all duration-200 hover:bg-green-50 hover:text-green-600" title="Input Timbangan">
                                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                    Timbang
                                </a>

                                <a href="{{ route('sheep.health-records.create', $s) }}" class="flex items-center justify-center flex-1 py-3 text-sm font-bold text-gray-700 transition-all duration-200 hover:bg-red-50 hover:text-red-600" title="Lapor Sakit">
                                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Lapor Sakit
                                </a>

                                <a href="{{ route('sheep.edit', $s) }}" class="flex items-center justify-center w-12 py-3 text-gray-500 transition-all duration-200 hover:bg-gray-100 hover:text-green-600" title="Edit Data Domba">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center border-2 border-gray-200 border-dashed rounded-3xl col-span-full bg-gradient-to-br from-gray-50 to-green-50">
                        <div class="flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-gradient-to-br from-green-100 to-emerald-100">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-700">Tidak Ada Data Domba</h3>
                        <p class="mb-6 text-gray-500">{{ request()->hasAny(['search', 'gender', 'shelter_id']) ? 'Tidak ada domba yang sesuai dengan filter Anda.' : 'Belum ada data domba yang terdaftar.' }}</p>
                        @if(request()->hasAny(['search', 'gender', 'shelter_id']))
                            <a href="{{ route('sheep.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Reset Pencarian
                            </a>
                        @else
                            <a href="{{ route('sheep.create') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Input Domba Pertama Anda
                            </a>
                        @endif
                    </div>
                @endforelse

            </div>

            {{-- PAGINATION (MODERN) --}}
            <div class="mt-10">
                <div class="flex items-center justify-center">
                    {{ $sheep->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- CUSTOM PAGINATION STYLING --}}
    <style>
        /* Modern Pagination Styling */
        nav[role="navigation"] {
            @apply flex justify-center;
        }

        nav[role="navigation"] > div {
            @apply flex items-center gap-2;
        }

        nav[role="navigation"] a,
        nav[role="navigation"] span {
            @apply inline-flex items-center justify-center min-w-[40px] h-10 px-3 text-sm font-bold transition-all duration-200 rounded-lg;
        }

        nav[role="navigation"] a {
            @apply text-gray-700 bg-white border-2 border-gray-200 hover:bg-green-50 hover:border-green-300 hover:text-green-700 hover:scale-105;
        }

        nav[role="navigation"] span[aria-current="page"] {
            @apply text-white bg-gradient-to-r from-green-600 to-emerald-600 border-2 border-green-600 shadow-lg;
        }

        nav[role="navigation"] span[aria-disabled="true"] {
            @apply text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-not-allowed;
        }

        nav[role="navigation"] svg {
            @apply w-5 h-5;
        }
    </style>
</x-app-layout>
