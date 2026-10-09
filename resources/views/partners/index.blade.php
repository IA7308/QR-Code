<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600">
                    <span class="text-2xl">🤝</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Manajemen Kemitraan') }}
                    </h2>
                    <p class="text-sm font-medium text-blue-600">Kelola mitra plasma peternakan</p>
                </div>
            </div>
            <a href="{{ route('users.create') }}">
                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    {{ __('Daftarkan Mitra Baru') }}
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- 1. STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Total Mitra --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-blue-400 to-indigo-600 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-blue-700 border-2 border-blue-200 rounded-full bg-blue-50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Aktif
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Total Mitra Aktif</p>
                        <h3 class="text-4xl font-black text-blue-600">
                            {{ $totalPartners }} <span class="text-lg font-normal text-gray-400">Orang</span>
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Partner terdaftar dalam sistem</p>
                    </div>
                </div>

                {{-- Populasi Plasma --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-green-400 to-emerald-600 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 group-hover:rotate-6">
                                <span class="text-3xl">🐑</span>
                            </div>
                            <div class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-green-700 border-2 border-green-200 rounded-full bg-green-50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Plasma
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Populasi Plasma</p>
                        <h3 class="text-4xl font-black text-green-600">
                            {{ $totalPlasmaSheep }} <span class="text-lg font-normal text-gray-400">Ekor</span>
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Total domba di mitra plasma</p>
                    </div>
                </div>

                {{-- Rata-rata Kepemilikan --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-purple-400 to-pink-600 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-purple-400 to-pink-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-purple-700 border-2 border-purple-200 rounded-full bg-purple-50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                Avg
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Rata-rata Kepemilikan</p>
                        <h3 class="text-4xl font-black text-purple-600">
                            {{ $totalPartners > 0 ? round($totalPlasmaSheep / $totalPartners) : 0 }} <span class="text-lg font-normal text-gray-400">Ekor</span>
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Per mitra plasma</p>
                    </div>
                </div>
            </div>

            {{-- 2. SEARCH BAR --}}
            <div class="mb-8 overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-gray-500 to-gray-700">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Pencarian Mitra</h3>
                        <p class="text-xs text-gray-500">Cari berdasarkan nama, email, atau lokasi</p>
                    </div>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('partners.index') }}">
                        <div class="flex flex-col gap-4 md:flex-row md:items-end">
                            <div class="flex-grow">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Kata Kunci Pencarian
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" id="search"
                                        class="block w-full py-3 pl-12 pr-4 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:outline-none"
                                        placeholder="Ketik nama mitra, email, atau lokasi..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('partners.index') }}" class="flex items-center gap-1 px-5 py-3 text-sm font-bold text-gray-700 transition-all duration-200 bg-gray-100 border-2 border-gray-200 rounded-xl hover:bg-gray-200 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 3. GRID DAFTAR MITRA --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($partners as $partner)
                    <div class="flex flex-col overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl group">

                        {{-- Header Kartu --}}
                        <div class="relative p-6 border-b-2 border-gray-100 bg-gradient-to-br from-blue-50 to-indigo-50">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 text-2xl font-black text-white transition-transform duration-300 transform shadow-lg rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 group-hover:rotate-6">
                                        {{ substr($partner->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4 overflow-hidden">
                                        <h3 class="text-lg font-black text-gray-900 truncate transition-colors group-hover:text-blue-600">
                                            <a href="{{ route('partners.show', $partner) }}">{{ $partner->name }}</a>
                                        </h3>
                                        <p class="flex items-center gap-1 text-xs font-semibold text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $partner->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Body Kartu --}}
                        <div class="flex-grow p-6 space-y-4">
                            {{-- Alamat --}}
                            <div class="flex items-start gap-3 p-3 transition-all duration-200 border-2 border-gray-100 rounded-xl hover:border-blue-200 hover:bg-blue-50">
                                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Alamat</p>
                                    <p class="text-sm font-medium text-gray-700 line-clamp-2">{{ $partner->address ?? 'Alamat belum diisi' }}</p>
                                </div>
                            </div>

                            {{-- Total Aset --}}
                            <div class="p-4 border-2 border-blue-200 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-blue-700 uppercase">Total Aset Plasma</p>
                                        <p class="mt-1 text-3xl font-black text-blue-600">
                                            {{ $partner->total_sheep }} <span class="text-base font-medium text-gray-500">Ekor</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-center shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600">
                                        <span class="text-2xl">🐑</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Kartu: Action Buttons --}}
                        <div class="flex border-t-2 border-gray-100 divide-x-2 divide-gray-100">
                            @if($partner->phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $partner->phone)) }}" target="_blank" class="flex items-center justify-center flex-1 gap-2 py-4 text-sm font-bold text-green-600 transition-all duration-200 hover:bg-green-50">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    WhatsApp
                                </a>
                            @else
                                <span class="flex items-center justify-center flex-1 py-4 text-sm font-semibold text-gray-400 cursor-not-allowed bg-gray-50">
                                    No Contact
                                </span>
                            @endif

                            <a href="{{ route('partners.show', $partner) }}" class="flex items-center justify-center flex-1 gap-2 py-4 text-sm font-bold text-blue-600 transition-all duration-200 hover:bg-blue-50">
                                Lihat Detail
                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center border-2 border-gray-200 border-dashed col-span-full rounded-3xl bg-gradient-to-br from-gray-50 to-blue-50">
                        <div class="flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-700">
                            {{ request('search') ? 'Tidak Ada Hasil' : 'Belum Ada Mitra' }}
                        </h3>
                        <p class="mb-6 text-gray-500">
                            {{ request('search') ? 'Tidak ada mitra yang cocok dengan pencarian Anda.' : 'Belum ada mitra plasma yang terdaftar dalam sistem.' }}
                        </p>
                        <a href="{{ request('search') ? route('partners.index') : route('users.create') }}">
                            <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request('search'))
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    @endif
                                </svg>
                                {{ request('search') ? 'Reset Pencarian' : 'Tambah Mitra Baru' }}
                            </button>
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $partners->links() }}
            </div>
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
            @apply text-gray-700 bg-white border-2 border-gray-200 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 hover:scale-105;
        }

        nav[role="navigation"] span[aria-current="page"] {
            @apply text-white bg-gradient-to-r from-blue-600 to-indigo-600 border-2 border-blue-600 shadow-lg;
        }

        nav[role="navigation"] span[aria-disabled="true"] {
            @apply text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-not-allowed;
        }

        nav[role="navigation"] svg {
            @apply w-5 h-5;
        }
    </style>
</x-app-layout>
