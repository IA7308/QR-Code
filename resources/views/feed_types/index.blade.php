<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-green-400 to-emerald-600">
                    <span class="text-2xl">🌾</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Jenis Pakan & Harga') }}
                    </h2>
                    <p class="text-sm font-medium text-green-600">Kelola database jenis pakan domba</p>
                </div>
            </div>
            <a href="{{ route('feed-types.create') }}">
                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Tambah Pakan') }}
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-green-50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            {{-- SUCCESS MESSAGE --}}
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

            {{-- SEARCH & FILTER BAR --}}
            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-gray-500 to-gray-700">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Filter & Pencarian</h3>
                            <p class="text-xs text-gray-500">Temukan jenis pakan yang Anda cari</p>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'partner_id']))
                        <a href="{{ route('feed-types.index') }}" class="flex items-center gap-1 px-4 py-2 text-xs font-bold text-red-600 transition-all duration-200 border-2 border-red-200 rounded-lg bg-red-50 hover:bg-red-100 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>

                <div class="p-6">
                    <form method="GET" action="{{ route('feed-types.index') }}">
                        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-12">

                            {{-- Cari Nama --}}
                            <div class="md:col-span-6">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Cari Jenis Pakan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        id="search"
                                        name="search"
                                        placeholder="Contoh: Rumput Odot..."
                                        value="{{ request('search') }}"
                                        class="w-full py-2.5 pl-12 pr-4 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
                                    />
                                </div>
                            </div>

                            {{-- Filter Mitra (Khusus Admin) --}}
                            @if(Auth::user()->role !== 'mitra' && isset($partners))
                                <div class="md:col-span-5">
                                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Penginput Data
                                    </label>
                                    <select
                                        name="partner_id"
                                        id="partner_id"
                                        class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none"
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
                                <div class="hidden md:block md:col-span-5"></div>
                            @endif

                            {{-- Tombol Cari --}}
                            <div class="md:col-span-1">
                                <button type="submit" class="flex items-center justify-center w-full gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- GRID JENIS PAKAN --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($feedTypes as $feed)
                    <div class="relative flex flex-col overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:shadow-2xl hover:scale-105 rounded-2xl">

                        {{-- Header Card --}}
                        <div class="p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-12 h-12 transition-transform duration-300 shadow-lg rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 group-hover:rotate-6">
                                        <span class="text-2xl">🌾</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 line-clamp-1" title="{{ $feed->name }}">
                                            {{ $feed->name }}
                                        </h3>
                                        {{-- Badge Mitra (Khusus Admin) --}}
                                        @if(Auth::user()->role !== 'mitra')
                                            <p class="flex items-center gap-1 text-xs font-semibold text-gray-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $feed->user->name ?? 'Unknown' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1.5 text-xs font-bold text-gray-700 border-2 border-gray-200 rounded-lg bg-gray-50">
                                    {{ $feed->unit }}
                                </span>
                            </div>
                        </div>

                        {{-- Body Card --}}
                        <div class="flex-grow p-5">
                            {{-- Harga --}}
                            <div class="flex items-end justify-between mb-4">
                                <span class="text-sm font-semibold text-gray-500">Harga per Unit:</span>
                                <span class="text-2xl font-black text-green-600">
                                    Rp {{ number_format($feed->price_per_unit, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="p-3 border-2 border-gray-100 rounded-xl bg-gray-50">
                                @if($feed->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $feed->description }}
                                    </p>
                                @else
                                    <p class="text-sm italic text-center text-gray-400">
                                        Tidak ada deskripsi
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="flex items-center justify-between px-5 py-4 border-t-2 border-gray-100 bg-gray-50">
                            <span class="flex items-center gap-1 text-xs font-semibold text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                ID: {{ $feed->id }}
                            </span>
                            <div class="flex gap-3">
                                <a href="{{ route('feed-types.edit', $feed) }}" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-blue-700 transition-all duration-200 border-2 border-blue-200 rounded-lg bg-blue-50 hover:bg-blue-100 hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('feed-types.destroy', $feed) }}" method="POST" onsubmit="return confirm('Hapus jenis pakan {{ $feed->name }}?');" class="inline">
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
                            <div class="flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-green-100">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-gray-900">
                                {{ request('search') || request('partner_id') ? 'Tidak Ada Hasil' : 'Belum Ada Data Pakan' }}
                            </h3>
                            <p class="mb-6 text-gray-500">
                                {{ request('search') || request('partner_id') ? 'Tidak ada jenis pakan yang cocok dengan filter Anda.' : 'Mulai dengan menambahkan jenis pakan pertama untuk sistem Anda.' }}
                            </p>
                            <a href="{{ route('feed-types.create') }}">
                                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Pakan Pertama
                                </button>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($feedTypes->hasPages())
                <div class="px-6 py-4 bg-white border-2 border-gray-100 shadow-lg rounded-2xl">
                    {{ $feedTypes->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- CUSTOM PAGINATION STYLING --}}
    <style>
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
