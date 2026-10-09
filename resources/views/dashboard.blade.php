<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div
                class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-green-400 to-emerald-600">
                <span class="text-2xl">📊</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-gray-800">
                    {{ __($dashboardTitle ?? 'Dashboard') }}
                </h2>
                <p class="text-sm font-medium text-green-600">Ringkasan & Statistik Peternakan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            {{-- BAGIAN 1: KARTU STATISTIK (KPI) - REDESIGNED --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">

                {{-- Total Domba --}}
                <div
                    class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 opacity-10 group-hover:scale-150">
                    </div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 group-hover:rotate-6">
                                <span class="text-3xl">🐑</span>
                            </div>
                            <div
                                class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-green-700 border-2 border-green-200 rounded-full bg-green-50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Aktif
                            </div>
                        </div>
                        <p class="mb-1 text-sm font-semibold text-gray-500">
                            {{ Auth::user()->role === 'admin' ? 'Total Populasi' : 'Domba Kelolaan' }}</p>
                        <p class="text-4xl font-black text-gray-900">{{ $totalSheep }} <span
                                class="text-lg font-normal text-gray-400">Ekor</span></p>
                    </div>
                </div>

                {{-- Domba Sakit --}}
                <div
                    class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full bg-gradient-to-br from-red-400 to-red-600 opacity-10 group-hover:scale-150">
                    </div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-red-400 to-red-600 group-hover:rotate-6">
                                <span class="text-3xl">🩺</span>
                            </div>
                            @if ($activeSicknessCount > 0)
                                <div
                                    class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-white bg-red-500 rounded-full shadow-md animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    Perhatian!
                                </div>
                            @endif
                        </div>
                        <p class="mb-1 text-sm font-semibold text-gray-500">Perlu Perawatan</p>
                        <p class="text-4xl font-black text-red-600">{{ $activeSicknessCount }} <span
                                class="text-lg font-normal text-gray-400">Ekor</span></p>
                    </div>
                </div>

                {{-- Domba Hamil --}}
                <div
                    class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 opacity-10 group-hover:scale-150">
                    </div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 group-hover:rotate-6">
                                <span class="text-3xl">🤰</span>
                            </div>
                            @if ($pregnantCount > 0)
                                <div
                                    class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-purple-700 border-2 border-purple-200 rounded-full bg-purple-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    Bunting
                                </div>
                            @endif
                        </div>
                        <p class="mb-1 text-sm font-semibold text-gray-500">Sedang Bunting</p>
                        <p class="text-4xl font-black text-purple-600">{{ $pregnantCount }} <span
                                class="text-lg font-normal text-gray-400">Indukan</span></p>
                    </div>
                </div>

                {{-- Saldo Bulan Ini --}}
                <div
                    class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 group-hover:scale-150 {{ $balanceThisMonth >= 0 ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-orange-400 to-orange-600' }}">
                    </div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="flex items-center justify-center w-14 h-14 transition-transform duration-300 transform rounded-xl shadow-lg group-hover:rotate-6 {{ $balanceThisMonth >= 0 ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-orange-400 to-orange-600' }}">
                                <span class="text-3xl">💰</span>
                            </div>
                            <div
                                class="flex items-center gap-1 px-3 py-1 text-xs font-bold border-2 rounded-full {{ $balanceThisMonth >= 0 ? 'text-green-700 border-green-200 bg-green-50' : 'text-orange-700 border-orange-200 bg-orange-50' }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if ($balanceThisMonth >= 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    @endif
                                </svg>
                                {{ $balanceThisMonth >= 0 ? 'Surplus' : 'Defisit' }}
                            </div>
                        </div>
                        <p class="mb-1 text-sm font-semibold text-gray-500">Saldo Bulan Ini</p>
                        <p
                            class="text-3xl font-black {{ $balanceThisMonth >= 0 ? 'text-green-600' : 'text-orange-600' }}">
                            Rp {{ number_format(abs($balanceThisMonth), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: GRAFIK STATISTIK (REDESIGNED) --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Grafik Keuangan --}}
                <div
                    class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl lg:col-span-2">
                    <div
                        class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                            <span class="text-xl">📊</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Arus Kas Tahun Ini</h3>
                            <p class="text-xs text-gray-500">Monitoring pemasukan & pengeluaran bulanan</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative w-full h-72">
                            <canvas id="financialChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Grafik Komposisi Pengeluaran (Pie Chart) --}}
                <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                    <div
                        class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-amber-500 to-orange-600">
                            <span class="text-xl">🍰</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Komposisi Biaya</h3>
                            <p class="text-xs text-gray-500">Distribusi pengeluaran</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative w-full h-72">
                            <canvas id="expensePieChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Grafik Pertumbuhan --}}
                <div
                    class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl lg:col-span-3">
                    <div
                        class="flex items-center gap-3 p-5 border-b-2 border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-blue-500 to-indigo-600">
                            <span class="text-xl">📈</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Tren Populasi Domba Baru</h3>
                            <p class="text-xs text-gray-500">Grafik pertambahan domba per bulan</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative w-full h-72">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- BAGIAN 3: PERINGATAN & DAFTAR (REDESIGNED) --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- KOLOM KIRI --}}
                <div class="space-y-6">
                    {{-- Domba Sakit --}}
                    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                        <div
                            class="flex items-center justify-between p-5 border-b-2 border-red-100 bg-gradient-to-r from-red-50 to-red-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 text-red-600 bg-red-200 rounded-lg shadow-md">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-red-800">Perlu Perawatan Segera</h3>
                                    <p class="text-xs text-red-600">{{ $activeSicknessCount }} domba memerlukan
                                        perhatian</p>
                                </div>
                            </div>
                            <a href="{{ route('sheep.index', ['filter_health' => 'sick']) }}"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white transition-all duration-200 bg-red-500 rounded-lg hover:bg-red-600 hover:scale-105 shadow-md">
                                Lihat Semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="p-5">
                            @forelse($sickSheepList as $health)
                                <div
                                    class="flex items-center justify-between p-4 mb-3 transition-all duration-200 border-2 border-gray-100 last:mb-0 rounded-xl hover:border-red-200 hover:bg-red-50 hover:shadow-md">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-100 rounded-lg">
                                            <span class="text-xl">🐑</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">
                                                <a href="{{ route('sheep.show', $health->sheep_id) }}"
                                                    class="transition-colors hover:text-green-600">
                                                    {{ $health->sheep->tag_number }}
                                                </a>
                                            </p>
                                            <p class="flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                    </path>
                                                </svg>
                                                {{ $health->sheep->shelter->name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-bold text-red-800 bg-red-100 border-2 border-red-200 rounded-lg shadow-sm">
                                            {{ $health->diagnosis }}
                                        </span>
                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($health->record_date)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-green-100 to-emerald-100">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Alhamdulillah! 🎉</p>
                                    <p class="text-xs text-gray-500">Tidak ada domba yang sakit</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Estimasi Kelahiran --}}
                    <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                        <div
                            class="flex items-center justify-between p-5 border-b-2 border-purple-100 bg-gradient-to-r from-purple-50 to-purple-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center w-10 h-10 text-purple-600 bg-purple-200 rounded-lg shadow-md">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-purple-800">Estimasi Kelahiran</h3>
                                    <p class="text-xs text-purple-600">Domba yang akan melahirkan</p>
                                </div>
                            </div>
                            <a href="#"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white transition-all duration-200 bg-purple-500 rounded-lg hover:bg-purple-600 hover:scale-105 shadow-md">
                                Lihat Semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="p-5">
                            @forelse($pregnantSheepList as $repro)
                                <div
                                    class="flex items-center justify-between p-4 mb-3 transition-all duration-200 border-2 border-gray-100 last:mb-0 rounded-xl hover:border-purple-200 hover:bg-purple-50 hover:shadow-md">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg">
                                            <span class="text-xl">🐑</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">
                                                <a href="{{ route('sheep.show', $repro->female_sheep_id) }}"
                                                    class="transition-colors hover:text-green-600">
                                                    {{ $repro->dam->tag_number }}
                                                </a>
                                            </p>
                                            <p class="flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                    </path>
                                                </svg>
                                                {{ $repro->dam->shelter->name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-purple-600">
                                            {{ \Carbon\Carbon::parse($repro->expected_delivery_date)->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($repro->expected_delivery_date)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-purple-100 to-purple-200">
                                        <span class="text-3xl">📋</span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Belum Ada Data</p>
                                    <p class="text-xs text-gray-500">Tidak ada kehamilan aktif saat ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Transaksi --}}
                <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl h-fit">
                    <div
                        class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                                <span class="text-xl">💸</span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800">Transaksi Terakhir</h3>
                                <p class="text-xs text-gray-500">Riwayat keuangan terbaru</p>
                            </div>
                        </div>
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('profit-loss.index') }}"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white transition-all duration-200 rounded-lg shadow-md bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:scale-105">
                                Buka Kas
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="p-5">
                        @forelse($recentTransactions as $transaction)
                            <div
                                class="flex items-center justify-between p-4 mb-3 transition-all duration-200 border-2 border-gray-100 last:mb-0 rounded-xl hover:shadow-md {{ $transaction->type == 'income' ? 'hover:border-green-200 hover:bg-green-50' : 'hover:border-red-200 hover:bg-red-50' }}">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-12 h-12 rounded-lg {{ $transaction->type == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        @if ($transaction->type == 'income')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $transaction->category }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->date->format('d M') }} •
                                            {{ Str::limit($transaction->description, 20) }}</p>
                                    </div>
                                </div>
                                <span
                                    class="text-base font-bold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'income' ? '+' : '-' }} Rp
                                    {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div
                                    class="flex items-center justify-center w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-gray-100 to-gray-200">
                                    <span class="text-3xl">💰</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Belum Ada Transaksi</p>
                                <p class="text-xs text-gray-500">Catat transaksi pertama Anda</p>
                            </div>
                        @endforelse

                        <div class="pt-4 mt-4 border-t-2 border-gray-100">
                            <a href="{{ route('profit-loss.create') }}">
                                <button
                                    class="flex items-center justify-center w-full gap-2 px-5 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Catat Transaksi Baru
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        {{-- CHART.JS DARI LOCAL --}}
        <script src="{{ asset('js/chart.min.js') }}"></script>

        <script>
            console.log('=== CHART SCRIPT LOADED ===');
            console.log('Chart.js available:', typeof Chart !== 'undefined');

            // Langsung eksekusi tanpa event listener
            (function() {
                const sheepData = @json(array_values(!empty($monthlySheep) ? $monthlySheep : array_fill(0, 12, 0)));
                const incomeData = @json(array_values(!empty($monthlyIncome) ? $monthlyIncome : array_fill(0, 12, 0)));
                const expenseData = @json(array_values(!empty($monthlyExpense) ? $monthlyExpense : array_fill(0, 12, 0)));

                const expenseLabels = @json($expenseLabels ?? []);
                const expenseTotals = @json($expenseTotals ?? []);

                console.log('Data loaded:');
                console.log('- Expense Labels:', expenseLabels);
                console.log('- Expense Totals:', expenseTotals);

                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                // 1. Financial Chart
                const financialCanvas = document.getElementById('financialChart');
                console.log('Financial canvas:', financialCanvas);

                if (financialCanvas) {
                    new Chart(financialCanvas, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                    label: 'Pemasukan',
                                    data: incomeData,
                                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    borderSkipped: false,
                                },
                                {
                                    label: 'Pengeluaran',
                                    data: expenseData,
                                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                    borderColor: 'rgba(239, 68, 68, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    borderSkipped: false,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        },
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    console.log('✅ Financial chart created');
                }

                // 2. Pie Chart
                const pieCanvas = document.getElementById('expensePieChart');
                console.log('Pie canvas:', pieCanvas);
                console.log('Pie data length:', expenseLabels.length);

                if (pieCanvas && expenseLabels.length > 0) {
                    new Chart(pieCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: expenseLabels,
                            datasets: [{
                                data: expenseTotals,
                                backgroundColor: [
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(251, 191, 36, 0.8)',
                                    'rgba(168, 85, 247, 0.8)',
                                    'rgba(236, 72, 153, 0.8)'
                                ],
                                borderColor: '#ffffff',
                                borderWidth: 3,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                    console.log('✅ Pie chart created');
                } else {
                    console.warn('⚠️ Pie chart skipped');
                }

                // 3. Growth Chart
                const growthCanvas = document.getElementById('growthChart');
                console.log('Growth canvas:', growthCanvas);

                if (growthCanvas) {
                    new Chart(growthCanvas, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Domba Baru (Ekor)',
                                data: sheepData,
                                borderColor: 'rgba(16, 185, 129, 1)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    console.log('✅ Growth chart created');
                }

                console.log('=== ALL CHARTS INITIALIZED ===');
            })();
        </script>
    @endpush

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sheepData = @json(array_values(!empty($monthlySheep) ? $monthlySheep : array_fill(0, 12, 0)));
            const incomeData = @json(array_values(!empty($monthlyIncome) ? $monthlyIncome : array_fill(0, 12, 0)));
            const expenseData = @json(array_values(!empty($monthlyExpense) ? $monthlyExpense : array_fill(0, 12, 0)));

            // Data untuk Pie Chart
            const expenseLabels = @json($expenseLabels ?? []);
            const expenseTotals = @json($expenseTotals ?? []);

            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            // 1. Grafik Keuangan (Bar) - MODERN STYLE
            const financialCanvas = document.getElementById('financialChart');
            if (financialCanvas) {
                new Chart(financialCanvas, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                                label: 'Pemasukan',
                                data: incomeData,
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false,
                            },
                            {
                                label: 'Pengeluaran',
                                data: expenseData,
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                borderRadius: 8,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + new Intl.NumberFormat(
                                            'id-ID').format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        weight: '600'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                            notation: "compact",
                                            compactDisplay: "short"
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Grafik Komposisi Pengeluaran (Doughnut - MODERN)
            const pieCanvas = document.getElementById('expensePieChart');
            if (pieCanvas && expenseLabels.length > 0) {
                new Chart(pieCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: expenseLabels,
                        datasets: [{
                            data: expenseTotals,
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(168, 85, 247, 0.8)',
                                'rgba(236, 72, 153, 0.8)'
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 12,
                                    font: {
                                        size: 11,
                                        weight: 'bold'
                                    },
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                borderRadius: 8,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ': Rp ' + new Intl.NumberFormat('id-ID')
                                            .format(context.parsed) + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            } else if (pieCanvas) {
                // Empty state untuk pie chart
                const ctx = pieCanvas.getContext('2d');
                ctx.fillStyle = '#E5E7EB';
                ctx.fillRect(0, 0, pieCanvas.width, pieCanvas.height);
                ctx.fillStyle = '#6B7280';
                ctx.font = '14px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('Belum ada data pengeluaran', pieCanvas.width / 2, pieCanvas.height / 2);
            }

            // 3. Grafik Pertumbuhan (Line - MODERN)
            const growthCanvas = document.getElementById('growthChart');
            if (growthCanvas) {
                new Chart(growthCanvas, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Domba Baru (Ekor)',
                            data: sheepData,
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: function(context) {
                                const chart = context.chart;
                                const {
                                    ctx,
                                    chartArea
                                } = chart;
                                if (!chartArea) return null;

                                const gradient = ctx.createLinearGradient(0, chartArea.bottom,
                                    0, chartArea.top);
                                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.05)');
                                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.3)');
                                return gradient;
                            },
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 8,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: 'rgba(16, 185, 129, 1)',
                            pointBorderWidth: 3,
                            pointHoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                borderRadius: 8,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        weight: '600'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    precision: 0,
                                    callback: function(value) {
                                        return value + ' ekor';
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }
        });
    </script> --}}
</x-app-layout>
