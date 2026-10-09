<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 h-screen transition-transform duration-300 transform -translate-x-full bg-white border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-auto">

    {{-- LOGIC PHP: HITUNG NOTIFIKASI --}}
    @php
        $pendingReqCount = 0;
        if(Auth::check() && Auth::user()->role === 'mitra') {
            try {
                $pendingReqCount = \App\Models\PlacementRequest::where('target_partner_id', Auth::id())
                    ->where('status', 'pending')
                    ->count();
            } catch (\Exception $e) {
                $pendingReqCount = 0;
            }
        }
    @endphp

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #10b981, #059669);
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #059669, #047857);
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #10b981 transparent;
        }

        /* Smooth animation for nav links */
        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, #10b981, #059669);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .nav-link.active::before {
            transform: translateX(0);
        }
    </style>

    <!-- HEADER: Logo & Tombol Close -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100 shrink-0 bg-gradient-to-r from-green-50 to-emerald-50">

        <!-- Logo Group -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 transition-transform duration-200 hover:scale-105">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-lg bg-gradient-to-br">
                <img src="{{ asset('img/logo.png') }}" alt="Logo JAS Farm" class="object-contain w-16 h-16"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span class="hidden text-xl text-white">🐑</span>
            </div>
            <div>
                <span class="text-lg font-bold text-gray-800">49Group</span>
                <p class="text-xs font-medium text-green-600">For Your Worth 90%</p>
            </div>
        </a>

        <!-- Tombol Close (Mobile) -->
        <button @click="sidebarOpen = false"
            class="p-2 text-gray-500 transition-all duration-200 rounded-lg lg:hidden hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-green-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Menu Utama -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">

        @if (Auth::user()->role !== 'qr_client')
        <x-nav-link :href="route('places.index')" :active="request()->routeIs('places.*')"
            class="nav-link active flex items-center px-4 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm">
            <span class="mr-3 text-xl">📍</span>
            {{ __('QR Tempat') }}
        </x-nav-link>
        @endif

        @if (Auth::user()->role === 'admin')
            <x-nav-link :href="route('admin.qr-templates.index')" :active="request()->routeIs('admin.qr-templates.*') || request()->routeIs('admin.qr-units.*')"
                class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.qr-templates.*') || request()->routeIs('admin.qr-units.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <span class="mr-3 text-xl">▦</span>
                {{ __('Dynamic QR Review') }}
            </x-nav-link>
                <a href="{{ route('admin.qr-templates.create') }}"
                class="ml-4 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm font-bold text-green-800 transition hover:border-green-300 hover:bg-green-100">
                <span aria-hidden="true" class="text-lg leading-none">+</span>
                <span>{{ __('Buat Template') }}</span>
            </a>
            <div class="ml-4 mt-2 space-y-1 border-l-2 border-gray-100 pl-3">
                <a href="{{ route('admin.qr-nfc.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr-nfc.*') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">QR + NFC</a>
                <a href="{{ route('admin.qr.nfc-reader') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr.nfc-reader') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">QR Universal NFC</a>
                <a href="{{ route('admin.qr.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr.dashboard') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
                <a href="{{ route('admin.qr.analytics') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr.analytics') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Analytics</a>
                <a href="{{ route('admin.qr.universal') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr.universal') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">QR Universal (GPS)</a>
                <a href="{{ route('admin.qr.help') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr.help') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Bantuan</a>
                <a href="{{ route('admin.qr-clients.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr-clients.*') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Klien</a>
                <a href="{{ route('admin.qr-plans.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr-plans.*') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Paket</a>
                <a href="{{ route('admin.qr-invoices.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.qr-invoices.*') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Pembayaran</a>
            </div>
        @endif

        @if (Auth::user()->role === 'qr_client')
            <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')" class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('client.dashboard') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard Klien</x-nav-link>
            <x-nav-link :href="route('client.plans')" :active="request()->routeIs('client.plans')" class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('client.plans') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">Paket Subscription</x-nav-link>
            <x-nav-link :href="route('client.units')" :active="request()->routeIs('client.units')" class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('client.units') ? 'bg-green-50 text-green-800' : 'text-gray-600 hover:bg-gray-50' }}">QR Saya</x-nav-link>
        @endif

        @if (false)
        {{-- GRUP 1: MANAJEMEN TERNAK / ASET --}}
        <div class="pt-5 pb-2">
            <p class="flex items-center gap-2 px-4 text-xs font-bold tracking-wider text-gray-500 uppercase">
                <span class="w-8 h-px bg-gradient-to-r from-gray-300 to-transparent"></span>
                {{ Auth::user()->role === 'mitra' ? 'Aset Saya' : 'Manajemen Ternak' }}
            </p>
        </div>

        {{-- Permintaan Masuk (KHUSUS MITRA) --}}
        @if (Auth::user()->role === 'mitra')
            <x-nav-link :href="route('placement-requests.index')" :active="request()->routeIs('placement-requests.*')"
                class="nav-link flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('placement-requests.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="flex items-center">
                    <span class="mr-3 text-xl">📥</span>
                    <span>{{ __('Permintaan Masuk') }}</span>
                </div>

                {{-- BADGE NOTIFIKASI --}}
                @if($pendingReqCount > 0)
                    <span class="flex items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full shadow-lg animate-pulse">
                        {{ $pendingReqCount }}
                    </span>
                @endif
            </x-nav-link>
        @endif

        {{-- Data Domba (SEMUA USER) --}}
        <x-nav-link :href="route('sheep.index')" :active="request()->routeIs('sheep.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('sheep.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">🐑</span>
            {{ __('Data Domba') }}
        </x-nav-link>

        {{-- Lokasi Kandang (SEMUA USER) --}}
        <x-nav-link :href="route('shelters.index')" :active="request()->routeIs('shelters.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('shelters.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">🏠</span>
            {{ __('Lokasi Kandang') }}
        </x-nav-link>

        <x-nav-link :href="route('places.index')" :active="request()->routeIs('places.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('places.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">📍</span>
            {{ __('Tempat & QR Tetap') }}
        </x-nav-link>

        {{-- Pakan Harian (SEMUA USER) --}}
        <x-nav-link :href="route('feeding-records.index')" :active="request()->routeIs('feeding-records.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('feeding-records.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">🍽️</span>
            {{ __('Pakan Harian') }}
        </x-nav-link>

        {{-- GRUP 2: DATA MASTER --}}
        <div class="pt-5 pb-2">
            <p class="flex items-center gap-2 px-4 text-xs font-bold tracking-wider text-gray-500 uppercase">
                <span class="w-8 h-px bg-gradient-to-r from-gray-300 to-transparent"></span>
                Data Master
            </p>
        </div>

        <x-nav-link :href="route('feed-types.index')" :active="request()->routeIs('feed-types.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('feed-types.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">🌾</span>
            {{ __('Jenis Pakan') }}
        </x-nav-link>

        <x-nav-link :href="route('symptoms.index')" :active="request()->routeIs('symptoms.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('symptoms.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">🩺</span>
            {{ __('Data Gejala') }}
        </x-nav-link>

        {{-- GRUP 3: KEUANGAN --}}
        <div class="pt-5 pb-2">
            <p class="flex items-center gap-2 px-4 text-xs font-bold tracking-wider text-gray-500 uppercase">
                <span class="w-8 h-px bg-gradient-to-r from-gray-300 to-transparent"></span>
                Keuangan
            </p>
        </div>

        <x-nav-link :href="route('profit-loss.index')" :active="request()->routeIs('profit-loss.*')"
            class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('profit-loss.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
            <span class="mr-3 text-xl">💰</span>
            {{ __('Arus Kas') }}
        </x-nav-link>

        {{-- GRUP 4: ADMIN AREA (Hanya Admin) --}}
        @if (Auth::user()->role === 'admin')
            <div class="pt-5 pb-2">
                <p class="flex items-center gap-2 px-4 text-xs font-bold tracking-wider text-gray-500 uppercase">
                    <span class="w-8 h-px bg-gradient-to-r from-gray-300 to-transparent"></span>
                    Admin Area
                </p>
            </div>

            {{-- Manajemen User --}}
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <span class="mr-3 text-xl">👥</span>
                {{ __('Manajemen User') }}
            </x-nav-link>

            {{-- Kemitraan --}}
            <x-nav-link :href="route('partners.index')" :active="request()->routeIs('partners.*')"
                class="nav-link flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('partners.*') ? 'active bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                <span class="mr-3 text-xl">🤝</span>
                {{ __('Data Mitra') }}
            </x-nav-link>
        @endif
        @endif
    </nav>

    <!-- FOOTER: User Profile & Logout -->
    <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-green-50 shrink-0">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 p-3 mb-3 transition-all duration-200 rounded-xl hover:bg-white hover:shadow-md group"
            title="Edit Profil">
            <div
                class="flex items-center justify-center w-10 h-10 font-bold text-white transition-all duration-200 rounded-full shadow-md bg-gradient-to-br from-green-500 to-emerald-600 group-hover:shadow-lg group-hover:scale-110">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-bold text-gray-900 truncate transition-colors group-hover:text-green-700">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs font-medium text-green-600 uppercase truncate">{{ Auth::user()->role }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 group-hover:translate-x-1 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-bold text-red-600 transition-all duration-200 rounded-xl bg-red-50 hover:bg-red-100 hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
