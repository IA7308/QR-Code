<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-green-400 to-emerald-600">
                    <span class="text-2xl">💰</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Keuangan & Arus Kas') }}
                    </h2>
                    <p class="text-sm font-medium text-green-600">Pantau kesehatan finansial peternakan Anda</p>
                </div>
            </div>
            <a href="{{ route('profit-loss.create') }}">
                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    {{ __('Catat Transaksi Baru') }}
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-green-50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="relative flex items-center gap-3 p-4 text-green-800 border-l-4 border-green-500 rounded-lg shadow-sm bg-green-50">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="font-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- 1. RINGKASAN SALDO (CARD MODERN) --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                {{-- SALDO --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-green-400 to-emerald-600 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            @if($balance >= 0)
                                <div class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-green-700 border-2 border-green-200 rounded-full bg-green-50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    Sehat
                                </div>
                            @else
                                <div class="flex items-center gap-1 px-3 py-1 text-xs font-bold text-red-700 border-2 border-red-200 rounded-full bg-red-50 animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                                    Defisit
                                </div>
                            @endif
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">
                            Saldo
                            @if(request('partner_id') == 'all')
                                <span class="text-blue-600">(Gabungan)</span>
                            @elseif(request('partner_id'))
                                <span class="text-blue-600">(Mitra Terpilih)</span>
                            @else
                                <span class="text-green-600">(Dompet Saya)</span>
                            @endif
                        </p>
                        <h3 class="text-4xl font-black {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($balance), 0, ',', '.') }}
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">{{ $balance >= 0 ? 'Keuangan dalam kondisi baik' : 'Perhatian diperlukan segera' }}</p>
                    </div>
                </div>

                {{-- PEMASUKAN --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 bg-green-400 rounded-full opacity-10 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-green-400 to-green-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Total Pemasukan</p>
                        <h3 class="text-3xl font-black text-green-600">
                            + Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Sesuai filter yang diterapkan</p>
                    </div>
                </div>

                {{-- PENGELUARAN --}}
                <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                    <div class="absolute top-0 right-0 w-32 h-32 transition-transform duration-300 transform translate-x-8 -translate-y-8 bg-red-400 rounded-full opacity-10 group-hover:scale-150"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-center transition-transform duration-300 transform shadow-lg w-14 h-14 rounded-xl bg-gradient-to-br from-red-400 to-red-600 group-hover:rotate-6">
                                <svg class="text-white w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            </div>
                        </div>
                        <p class="mb-2 text-sm font-semibold text-gray-500 uppercase">Total Pengeluaran</p>
                        <h3 class="text-3xl font-black text-red-600">
                            - Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </h3>
                        <p class="mt-2 text-xs text-gray-400">Sesuai filter yang diterapkan</p>
                    </div>
                </div>
            </div>

            {{-- 2. FILTER BAR (MODERN) --}}
            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-gray-500 to-gray-700">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Filter Transaksi</h3>
                            <p class="text-xs text-gray-500">Saring data sesuai kebutuhan</p>
                        </div>
                    </div>
                    @if(request()->hasAny(['start_date', 'end_date', 'type', 'partner_id']))
                        <a href="{{ route('profit-loss.index') }}" class="flex items-center gap-1 px-4 py-2 text-xs font-bold text-red-600 transition-all duration-200 border-2 border-red-200 rounded-lg bg-red-50 hover:bg-red-100 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('profit-loss.index') }}">
                        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-5">

                            {{-- Filter Tanggal Mulai --}}
                            <div>
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Dari Tanggal
                                </label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none">
                            </div>

                            {{-- Filter Tanggal Akhir --}}
                            <div>
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Sampai Tanggal
                                </label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none">
                            </div>

                            {{-- Filter Mitra (KHUSUS ADMIN) --}}
                            @if(Auth::user()->role !== 'mitra' && isset($partners))
                                <div>
                                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Pemilik Dompet
                                    </label>
                                    <select name="partner_id" class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none">
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
                                <div class="hidden md:block"></div>
                            @endif

                            {{-- Filter Tipe --}}
                            <div>
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    Jenis Transaksi
                                </label>
                                <select name="type" class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none">
                                    <option value="">📊 Semua Transaksi</option>
                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>⬆️ Pemasukan (+)</option>
                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>⬇️ Pengeluaran (-)</option>
                                </select>
                            </div>

                            {{-- Tombol Terapkan --}}
                            <div>
                                <button type="submit" class="flex items-center justify-center w-full gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 3. DAFTAR TRANSAKSI (MODERN TIMELINE STYLE) --}}
            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-green-50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Riwayat Transaksi</h3>
                            <p class="text-xs text-green-600">Catatan pemasukan & pengeluaran</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-gray-700 border-2 border-gray-200 rounded-lg bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        {{ $records->total() }} Transaksi
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    @forelse ($records as $record)
                        <div class="flex flex-col justify-between gap-4 p-5 transition-all duration-200 border-2 border-gray-100 hover:border-green-200 hover:bg-green-50 hover:shadow-md md:flex-row md:items-center group rounded-xl">

                            {{-- BAGIAN KIRI: Ikon + Info --}}
                            <div class="flex items-start gap-4">
                                {{-- Icon dengan animasi --}}
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 transition-transform duration-200 transform rounded-xl shadow-lg group-hover:scale-110 {{ $record->type === 'income' ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-red-400 to-red-600' }}">
                                        @if($record->type === 'income')
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        @endif
                                    </div>
                                </div>

                                {{-- Info Detail --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h4 class="text-base font-bold text-gray-900">{{ $record->category }}</h4>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="flex items-center gap-1 text-xs font-semibold text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $record->date->format('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="mb-3 text-sm text-gray-600">{{ $record->description ?? 'Tidak ada catatan tambahan.' }}</p>

                                    {{-- Badges Info --}}
                                    <div class="flex flex-wrap gap-2">
                                        {{-- Badge Pemilik (Khusus Admin) --}}
                                        @if(Auth::user()->role !== 'mitra')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-gray-700 border-2 border-gray-200 rounded-lg bg-gray-50">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $record->user->name ?? 'Unknown' }}
                                            </span>
                                        @endif

                                        @if($record->sheep)
                                            <a href="{{ route('sheep.show', $record->sheep) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-green-700 border-2 border-green-200 rounded-lg bg-green-50 hover:bg-green-100 transition-colors">
                                                <span>🐑</span>
                                                {{ $record->sheep->tag_number }}
                                            </a>
                                        @endif
                                        @if($record->shelter)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-orange-700 border-2 border-orange-200 rounded-lg bg-orange-50">
                                                <span>🏠</span>
                                                {{ $record->shelter->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- BAGIAN KANAN: Nominal & Aksi --}}
                            <div class="flex items-center justify-between w-full gap-4 pl-16 md:justify-end md:w-auto md:pl-0">
                                <div class="text-right">
                                    <span class="block text-2xl font-black {{ $record->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $record->type === 'income' ? '+' : '-' }} Rp {{ number_format($record->amount, 0, ',', '.') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 mt-1 text-xs font-bold border-2 rounded-lg {{ $record->type === 'income' ? 'text-green-700 border-green-200 bg-green-50' : 'text-red-700 border-red-200 bg-red-50' }}">
                                        {{ $record->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </div>

                                {{-- Tombol Hapus --}}
                                <div class="transition-opacity opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                    <form action="{{ route('profit-loss.destroy', $record) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini? Saldo akan dihitung ulang.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 text-gray-400 transition-all duration-200 bg-white border-2 border-gray-200 rounded-lg hover:text-red-600 hover:bg-red-50 hover:border-red-300 hover:scale-110" title="Hapus Transaksi">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-green-100">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-gray-900">Belum Ada Transaksi</h3>
                            <p class="mb-6 text-gray-500">Mulai catat pemasukan dan pengeluaran Anda untuk monitoring keuangan yang lebih baik.</p>
                            <a href="{{ route('profit-loss.create') }}">
                                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Catat Transaksi Pertama
                                </button>
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($records->hasPages())
                    <div class="px-6 py-4 border-t-2 border-gray-100 bg-gray-50">
                        {{ $records->links() }}
                    </div>
                @endif
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
