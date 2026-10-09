<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600">
                    <span class="text-2xl">🤝</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Profil Mitra Plasma') }}
                    </h2>
                    <p class="text-sm font-medium text-blue-600">Detail lengkap informasi mitra</p>
                </div>
            </div>
            <a href="{{ route('partners.index') }}">
                <button class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-700 transition-all duration-200 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:scale-105 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            {{-- 1. HEADER PROFIL & KONTAK --}}
            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col items-start gap-6 md:flex-row md:items-center md:justify-between">
                        {{-- Avatar & Info --}}
                        <div class="flex items-center gap-6">
                            <div class="flex items-center justify-center flex-shrink-0 w-24 h-24 text-4xl font-black text-white shadow-xl rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600">
                                {{ substr($partner->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="mb-2 text-3xl font-black text-gray-900">{{ $partner->name }}</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="font-medium">{{ $partner->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <span class="font-medium">{{ $partner->address ?? 'Alamat belum diisi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3">
                            @if($partner->phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $partner->phone)) }}" target="_blank" class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-xl hover:scale-105">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    WhatsApp
                                </a>
                            @else
                                <button disabled class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-not-allowed rounded-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    No HP Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. STATISTIK RINGKAS (KPI CARDS) --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

                {{-- Total Populasi --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-20 h-20 transition-transform duration-300 transform translate-x-6 -translate-y-6 rounded-full opacity-10 bg-gradient-to-br from-green-400 to-emerald-600"></div>
                    <div class="relative p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-400 to-emerald-600">
                                <span class="text-xl">🐑</span>
                            </div>
                        </div>
                        <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Total Populasi</p>
                        <p class="text-3xl font-black text-green-600">{{ $stats['total'] }} <span class="text-sm font-normal text-gray-400">Ekor</span></p>
                    </div>
                </div>

                {{-- Gender Split --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-20 h-20 transition-transform duration-300 transform translate-x-6 -translate-y-6 rounded-full opacity-10 bg-gradient-to-br from-blue-400 to-pink-500"></div>
                    <div class="relative p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-blue-400 to-indigo-500">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                        <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Jantan / Betina</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-black text-blue-600">{{ $stats['male'] }}</span>
                            <span class="text-gray-300">/</span>
                            <span class="text-xl font-black text-pink-600">{{ $stats['female'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Status Kesehatan --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-20 h-20 transition-transform duration-300 transform translate-x-6 -translate-y-6 rounded-full opacity-10 {{ $stats['sick'] > 0 ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-green-400 to-emerald-600' }}"></div>
                    <div class="relative p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md {{ $stats['sick'] > 0 ? 'bg-gradient-to-br from-red-400 to-red-600' : 'bg-gradient-to-br from-green-400 to-emerald-600' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            @if($stats['sick'] > 0)
                                <div class="flex items-center gap-1 px-2 py-1 text-xs font-bold text-red-700 border-2 border-red-200 rounded-lg bg-red-50 animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Perhatian
                                </div>
                            @else
                                <div class="flex items-center gap-1 px-2 py-1 text-xs font-bold text-green-700 border-2 border-green-200 rounded-lg bg-green-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sehat
                                </div>
                            @endif
                        </div>
                        <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Status Kesehatan</p>
                        @if($stats['sick'] > 0)
                            <p class="text-3xl font-black text-red-600">{{ $stats['sick'] }} <span class="text-sm font-normal text-red-400">Sakit</span></p>
                        @else
                            <p class="text-xl font-black text-green-600">Semua Sehat</p>
                        @endif
                    </div>
                </div>

                {{-- Kapasitas Kandang --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-20 h-20 transition-transform duration-300 transform translate-x-6 -translate-y-6 rounded-full opacity-10 bg-gradient-to-br from-purple-400 to-indigo-600"></div>
                    <div class="relative p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-purple-400 to-indigo-600">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                        </div>
                        <p class="mb-1 text-xs font-bold text-gray-500 uppercase">Okupansi Kandang</p>
                        <div class="flex items-baseline gap-1 mb-2">
                            <span class="text-2xl font-black text-purple-600">{{ $stats['capacity_used'] }}</span>
                            <span class="text-sm font-semibold text-gray-400">/ {{ $stats['total_capacity'] }}</span>
                        </div>
                        {{-- Progress Bar --}}
                        @php
                            $percent = $stats['total_capacity'] > 0 ? ($stats['capacity_used'] / $stats['total_capacity']) * 100 : 0;
                            $barColor = $percent > 90 ? 'bg-red-500' : ($percent > 70 ? 'bg-yellow-500' : 'bg-green-500');
                        @endphp
                        <div class="w-full h-2 overflow-hidden bg-gray-200 rounded-full">
                            <div class="h-full transition-all duration-300 {{ $barColor }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

                {{-- KOLOM KIRI: DAFTAR KANDANG (1/3) --}}
                <div class="space-y-6 lg:col-span-1">
                    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                        <div class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-purple-50">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-purple-500 to-indigo-600">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800">Daftar Kandang</h3>
                                <p class="text-xs text-purple-600">{{ count($shelters) }} Kandang terdaftar</p>
                            </div>
                        </div>
                        <div class="p-5 space-y-3">
                            @forelse($shelters as $shelter)
                                <div class="flex items-center justify-between p-4 transition-all duration-200 border-2 border-gray-100 hover:border-purple-200 hover:bg-purple-50 rounded-xl">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                            <span class="text-xl">🏠</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $shelter->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $shelter->location ?? 'Lokasi tidak diisi' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-base font-black {{ $shelter->sheep_count >= $shelter->capacity ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $shelter->sheep_count }}
                                        </span>
                                        <span class="text-xs text-gray-400">/ {{ $shelter->capacity }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-gray-100 to-gray-200">
                                        <span class="text-3xl">🏚️</span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Belum Ada Kandang</p>
                                    <p class="text-xs text-gray-500">Tidak ada data kandang tercatat</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: INVENTARIS DOMBA (2/3) --}}
                <div class="lg:col-span-2">
                    <div class="mb-6">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                                <span class="text-xl">🐑</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Inventaris Domba</h3>
                                <p class="text-sm text-green-600">Daftar domba yang dikelola mitra</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($sheep as $s)
                            <a href="{{ route('sheep.show', $s) }}" class="block transition-all duration-300 transform hover:scale-105 group">
                                <div class="flex flex-col h-full overflow-hidden bg-white shadow-lg rounded-2xl hover:shadow-2xl">
                                    {{-- Gambar --}}
                                    <div class="relative h-40 overflow-hidden bg-gray-100">
                                        <img
                                            class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                                            src="{{ $s->photo_path ? asset('storage/' . $s->photo_path) : 'https://placehold.co/400x300/d1fae5/047857?text=' . urlencode($s->tag_number) }}"
                                            alt="Foto Domba {{ $s->tag_number }}"
                                        >
                                        {{-- Badges Overlay --}}
                                        <div class="absolute top-2 right-2">
                                            @if($s->is_pedigree)
                                                <span class="flex items-center gap-1 px-2 py-1 text-xs font-bold text-white rounded-lg shadow-md bg-gradient-to-r from-amber-400 to-yellow-500">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                    UNGGUL
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Gender Badge --}}
                                        <div class="absolute bottom-2 left-2">
                                            <span class="flex items-center px-2 py-1 text-xs font-bold border-2 rounded-lg backdrop-blur-sm {{ $s->gender == 'Jantan' ? 'bg-blue-50/90 text-blue-700 border-blue-200' : 'bg-pink-50/90 text-pink-700 border-pink-200' }}">
                                                {{ $s->gender == 'Jantan' ? '♂️ Jantan' : '♀️ Betina' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Info --}}
                                    <div class="p-4">
                                        <h4 class="mb-2 text-base font-black text-gray-900 transition-colors group-hover:text-green-600">{{ $s->tag_number }}</h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            <span class="font-medium truncate">{{ $s->shelter->name ?? 'Tanpa Kandang' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-center border-2 border-gray-200 border-dashed col-span-full rounded-3xl bg-gradient-to-br from-gray-50 to-green-50">
                                <div class="flex items-center justify-center w-20 h-20 mb-4 rounded-full bg-gradient-to-br from-green-100 to-emerald-100">
                                    <span class="text-4xl">🐑</span>
                                </div>
                                <h3 class="mb-2 text-lg font-bold text-gray-700">Belum Ada Domba</h3>
                                <p class="mb-6 text-sm text-gray-500">Mitra ini belum memiliki domba dalam sistemnya.</p>
                                <a href="{{ route('sheep.create') }}">
                                    <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        Tambah Domba Baru
                                    </button>
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $sheep->links() }}
                    </div>
                </div>

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
