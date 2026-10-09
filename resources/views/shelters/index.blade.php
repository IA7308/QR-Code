<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-orange-400 to-red-600">
                    <span class="text-2xl">🏠</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Manajemen Kandang') }}
                    </h2>
                    <p class="text-sm font-medium text-orange-600">Kelola & monitor kapasitas kandang</p>
                </div>
            </div>
            <a href="{{ route('shelters.create') }}">
                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-orange-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Tambah Kandang') }}
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-orange-50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            {{-- SUCCESS & ERROR MESSAGES --}}
            @if(session('success'))
                <div class="relative flex items-center gap-3 p-4 text-green-800 border-l-4 border-green-500 rounded-lg shadow-sm bg-green-50">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="relative flex items-center gap-3 p-4 text-red-800 border-l-4 border-red-500 rounded-lg shadow-sm bg-red-50">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-bold">Error!</span> {{ session('error') }}
                    </div>
                </div>
            @endif

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Total Kandang --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 bg-orange-400 rounded-full opacity-10 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-orange-400 to-red-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Total Kandang</p>
                        <h3 class="text-4xl font-black text-orange-600">
                            {{ $shelters->total() }}
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Unit kandang terdaftar</p>
                    </div>
                </div>

                {{-- Total Kapasitas --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 bg-blue-400 rounded-full opacity-10 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Total Kapasitas</p>
                        <h3 class="text-4xl font-black text-blue-600">
                            {{ $shelters->sum('capacity') }}+
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Maksimal ekor domba</p>
                    </div>
                </div>

                {{-- Total Terisi --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 bg-green-400 rounded-full opacity-10 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Total Terisi (Hal. Ini)</p>
                        @php $totalFilled = $shelters->sum('sheep_count'); @endphp
                        <h3 class="text-4xl font-black text-green-600">
                            {{ $totalFilled }}
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Ekor domba saat ini</p>
                    </div>
                </div>
            </div>

            {{-- SEARCH & FILTER BAR --}}
            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-orange-50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-gray-500 to-gray-700">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Filter & Pencarian</h3>
                            <p class="text-xs text-gray-500">Temukan kandang yang Anda cari</p>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'partner_id', 'sort']))
                        <a href="{{ route('shelters.index') }}" class="flex items-center gap-1 px-4 py-2 text-xs font-bold text-red-600 transition-all duration-200 border-2 border-red-200 rounded-lg bg-red-50 hover:bg-red-100 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>

                <div class="p-6">
                    <form method="GET" action="{{ route('shelters.index') }}">
                        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-12">

                            {{-- Cari Nama --}}
                            <div class="md:col-span-5">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Cari Nama Kandang
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        id="search"
                                        name="search"
                                        type="text"
                                        placeholder="Kandang A..."
                                        value="{{ request('search') }}"
                                        class="w-full py-2.5 pl-12 pr-4 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 focus:outline-none"
                                    />
                                </div>
                            </div>

                            {{-- Filter Mitra (Hanya Admin) --}}
                            @if(Auth::user()->role !== 'mitra' && isset($partners))
                                <div class="md:col-span-3">
                                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Pemilik Kandang
                                    </label>
                                    <select
                                        name="partner_id"
                                        id="partner_id"
                                        class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 focus:outline-none"
                                    >
                                        <option value="">💼 Data Saya (Admin)</option>
                                        <option value="all" {{ request('partner_id') == 'all' ? 'selected' : '' }}>🌍 Semua Data (All)</option>
                                        @foreach($partners as $p)
                                            <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>
                                                🤝 {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="hidden md:block md:col-span-3"></div>
                            @endif

                            {{-- Sortir --}}
                            <div class="md:col-span-3">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                    </svg>
                                    Urutkan
                                </label>
                                <select
                                    name="sort"
                                    id="sort"
                                    onchange="this.form.submit()"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 focus:outline-none"
                                >
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>📝 Nama (A-Z)</option>
                                    <option value="fullest" {{ request('sort') == 'fullest' ? 'selected' : '' }}>🔴 Paling Penuh</option>
                                    <option value="emptiest" {{ request('sort') == 'emptiest' ? 'selected' : '' }}>🟢 Paling Kosong</option>
                                    <option value="capacity_high" {{ request('sort') == 'capacity_high' ? 'selected' : '' }}>📊 Kapasitas Terbesar</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>🆕 Terbaru Dibuat</option>
                                </select>
                            </div>

                            {{-- Tombol Cari --}}
                            <div class="md:col-span-1">
                                <button type="submit" class="flex items-center justify-center w-full gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl shadow-lg bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-orange-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- GRID KANDANG --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($shelters as $shelter)
                    @php
                        $currentCount = $shelter->sheep_count;
                        $percentage = ($shelter->capacity > 0) ? ($currentCount / $shelter->capacity) * 100 : 0;

                        $colorClass = 'from-green-400 to-emerald-600';
                        $barColor = 'bg-gradient-to-r from-green-400 to-emerald-600';
                        $statusText = 'Tersedia';
                        $statusBadge = 'text-green-700 border-green-200 bg-green-50';

                        if ($percentage >= 100) {
                            $colorClass = 'from-red-500 to-red-700';
                            $barColor = 'bg-gradient-to-r from-red-500 to-red-700';
                            $statusText = 'Penuh';
                            $statusBadge = 'text-red-700 border-red-200 bg-red-50 animate-pulse';
                        } elseif ($percentage >= 80) {
                            $colorClass = 'from-yellow-400 to-orange-600';
                            $barColor = 'bg-gradient-to-r from-yellow-400 to-orange-600';
                            $statusText = 'Hampir Penuh';
                            $statusBadge = 'text-yellow-700 border-yellow-200 bg-yellow-50';
                        }
                    @endphp

                    <div class="relative flex flex-col overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:shadow-2xl hover:scale-105 rounded-2xl">

                        {{-- Header Card --}}
                        <div class="p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-orange-50">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-12 h-12 transition-transform duration-300 shadow-lg rounded-xl bg-gradient-to-br {{ $colorClass }} group-hover:rotate-6">
                                        <span class="text-2xl">🏠</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 line-clamp-1" title="{{ $shelter->name }}">
                                            {{ $shelter->name }}
                                        </h3>
                                        {{-- Badge Mitra (Khusus Admin) --}}
                                        @if(Auth::user()->role !== 'mitra')
                                            <p class="flex items-center gap-1 text-xs font-semibold text-gray-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $shelter->user->name ?? 'Unknown' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1.5 text-xs font-bold border-2 rounded-lg {{ $statusBadge }}">
                                    {{ $statusText }}  
                                </span>
                            </div>
                        </div>

                        {{-- Body Card --}}
                        <div class="flex-grow p-5">
                            {{-- Kapasitas Angka --}}
                            <div class="flex items-end justify-between mb-3">
                                <div>
                                    <span class="text-4xl font-black text-gray-900">{{ $currentCount }}</span>
                                    <span class="text-lg font-semibold text-gray-400">/ {{ $shelter->capacity }}</span>
                                    <span class="text-sm font-medium text-gray-500">Ekor</span>
                                </div>
                                <div class="px-3 py-1.5 text-sm font-bold text-gray-700 border-2 border-gray-200 rounded-lg bg-gray-50">
                                    {{ round($percentage) }}%
                                </div>
                            </div>

                            {{-- Progress Bar Modern --}}
                            <div class="relative w-full h-4 mb-4 overflow-hidden bg-gray-200 rounded-full">
                                <div class="{{ $barColor }} h-full transition-all duration-700 ease-out shadow-lg" style="width: {{ $percentage > 100 ? 100 : $percentage }}%"></div>
                                @if($percentage >= 100)
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-xs font-bold text-white drop-shadow-lg">PENUH!</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Deskripsi --}}
                            <div class="p-3 border-2 border-gray-100 rounded-xl bg-gray-50">
                                <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">
                                    {{ $shelter->description ?? 'Tidak ada deskripsi tambahan untuk kandang ini.' }}
                                </p>
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="flex items-center justify-between px-5 py-4 border-t-2 border-gray-100 bg-gray-50">
                            <span class="flex items-center gap-1 text-xs font-semibold text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                ID: {{ $shelter->id }}
                            </span>
                            <div class="flex gap-3">
                                <a href="{{ route('shelters.edit', $shelter) }}" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-green-700 transition-all duration-200 border-2 border-green-200 rounded-lg bg-green-50 hover:bg-green-100 hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('shelters.destroy', $shelter) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Kandang {{ $shelter->name }}? Pastikan tidak ada domba di dalamnya.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-red-700 transition-all duration-200 border-2 border-red-200 rounded-lg bg-red-50 hover:bg-red-100 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-1 py-16 text-center md:col-span-2 lg:col-span-3">
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-orange-100">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-gray-900">
                                {{ request('search') || request('partner_id') ? 'Tidak Ada Hasil' : 'Belum Ada Kandang' }}
                            </h3>
                            <p class="mb-6 text-gray-500">
                                {{ request('search') || request('partner_id') ? 'Tidak ada kandang yang cocok dengan filter Anda.' : 'Mulai dengan membuat kandang pertama untuk mengelola domba Anda.' }}
                            </p>
                            <a href="{{ route('shelters.create') }}">
                                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 hover:shadow-xl hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Buat Kandang Pertama
                                </button>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($shelters->hasPages())
                <div class="px-6 py-4 bg-white border-2 border-gray-100 shadow-lg rounded-2xl">
                    {{ $shelters->links() }}
                </div>
            @endif

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
            @apply text-gray-700 bg-white border-2 border-gray-200 hover:bg-orange-50 hover:border-orange-300 hover:text-orange-700 hover:scale-105;
        }

        nav[role="navigation"] span[aria-current="page"] {
            @apply text-white bg-gradient-to-r from-orange-600 to-red-600 border-2 border-orange-600 shadow-lg;
        }

        nav[role="navigation"] span[aria-disabled="true"] {
            @apply text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-not-allowed;
        }

        nav[role="navigation"] svg {
            @apply w-5 h-5;
        }

        /* Progress Bar Animation */
        @keyframes fillProgress {
            from {
                width: 0%;
            }
        }

        /* Card Hover Effects */
        .group:hover .group-hover\:rotate-6 {
            transform: rotate(6deg);
        }

        /* Pulse Animation untuk Status Penuh */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</x-app-layout>
