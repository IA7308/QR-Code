<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-amber-400 to-orange-600">
                    <span class="text-2xl">🌾</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Riwayat Pemberian Pakan') }}
                    </h2>
                    <p class="text-sm font-medium text-amber-600">Monitoring nutrisi & feeding schedule</p>
                </div>
            </div>
            <a href="{{ route('feeding-records.create') }}">
                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-amber-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Catat Pakan Baru') }}
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-amber-50">
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

            {{-- FILTER SECTION --}}
            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-gradient-to-r from-gray-50 to-amber-50">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-gray-500 to-gray-700">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Filter Data Pakan</h3>
                            <p class="text-xs text-gray-500">Saring berdasarkan tanggal & lokasi</p>
                        </div>
                    </div>
                    @if(request()->hasAny(['start_date', 'end_date', 'shelter_id', 'partner_id']))
                        <a href="{{ route('feeding-records.index') }}" class="flex items-center gap-1 px-4 py-2 text-xs font-bold text-red-600 transition-all duration-200 border-2 border-red-200 rounded-lg bg-red-50 hover:bg-red-100 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                </div>

                <div class="p-6">
                    <form method="GET" action="{{ route('feeding-records.index') }}">
                        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-12">

                            {{-- Dari Tanggal --}}
                            <div class="md:col-span-3">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Dari Tanggal
                                </label>
                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    value="{{ request('start_date') }}"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 focus:outline-none"
                                />
                            </div>

                            {{-- Sampai Tanggal --}}
                            <div class="md:col-span-3">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Sampai Tanggal
                                </label>
                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                    value="{{ request('end_date') }}"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 focus:outline-none"
                                />
                            </div>

                            {{-- Filter Mitra (Khusus Admin) --}}
                            @if(Auth::user()->role !== 'mitra')
                                <div class="md:col-span-3">
                                    <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Pilih Data Mitra
                                    </label>
                                    <select
                                        name="partner_id"
                                        id="partner_id"
                                        class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 focus:outline-none"
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

                            {{-- Filter Kandang --}}
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 mb-2 text-sm font-bold text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Filter Kandang
                                </label>
                                <select
                                    name="shelter_id"
                                    id="shelter_id"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-700 transition-all border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-100 focus:outline-none"
                                >
                                    <option value="">🏠 Semua Kandang</option>
                                    @if(isset($shelters))
                                        @foreach($shelters as $shelter)
                                            <option value="{{ $shelter->id }}" {{ request('shelter_id') == $shelter->id ? 'selected' : '' }}>
                                                {{ $shelter->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Tombol Filter --}}
                            <div class="md:col-span-1">
                                <button type="submit" class="flex items-center justify-center w-full gap-2 px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 rounded-xl shadow-lg bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-amber-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- GRID FEEDING RECORDS --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($feedingRecords as $record)
                    @php
                        $totalCostPerCard = 0;
                        $hasMorning = false;
                        $hasEvening = false;

                        foreach($record->feedTypes as $feed) {
                            if($feed->pivot->quantity_morning > 0) {
                                $hasMorning = true;
                                $totalCostPerCard += ($feed->pivot->quantity_morning * ($feed->price_per_unit ?? 0));
                            }
                            if($feed->pivot->quantity_evening > 0) {
                                $hasEvening = true;
                                $totalCostPerCard += ($feed->pivot->quantity_evening * ($feed->price_per_unit ?? 0));
                            }
                        }
                    @endphp

                    <div class="relative flex flex-col overflow-hidden transition-all duration-300 transform bg-white shadow-lg group hover:shadow-2xl hover:scale-105 rounded-2xl">

                        <a href="{{ route('feeding-records.show', $record) }}" class="flex-grow block">
                            {{-- Header --}}
                            <div class="p-5 border-b-2 border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-12 h-12 transition-transform duration-300 shadow-lg rounded-xl bg-gradient-to-br from-amber-400 to-orange-600 group-hover:rotate-6">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">
                                                {{ $record->date->format('d M Y') }}
                                            </h3>
                                            <p class="text-xs text-gray-500">
                                                {{ $record->date->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    {{-- Badge Kandang --}}
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-orange-700 border-2 border-orange-200 rounded-lg bg-orange-50">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        {{ $record->shelter->name }}
                                    </span>

                                    {{-- Badge Mitra (Admin Only) --}}
                                    @if(Auth::user()->role !== 'mitra')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-blue-700 border-2 border-blue-200 rounded-lg bg-blue-50">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $record->user->name ?? 'Unknown' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Body: Dua Kolom Pagi & Sore --}}
                            <div class="p-5">
                                <div class="grid grid-cols-2 gap-4">

                                    {{-- Kolom Pagi --}}
                                    <div class="pr-3 border-r-2 border-gray-200 border-dashed">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center justify-center rounded-lg w-7 h-7 bg-gradient-to-br from-orange-400 to-yellow-500">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-orange-600 uppercase">Pagi</span>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-orange-700 border border-orange-200 rounded bg-orange-50">
                                                {{ $record->time_morning ? \Carbon\Carbon::parse($record->time_morning)->format('H:i') : '-' }}
                                            </span>
                                        </div>

                                        <ul class="space-y-2">
                                            @foreach($record->feedTypes as $feed)
                                                @if($feed->pivot->quantity_morning > 0)
                                                    <li class="flex items-center justify-between p-2 transition-colors border border-gray-100 rounded-lg hover:bg-orange-50">
                                                        <span class="text-xs font-medium text-gray-600 truncate" title="{{ $feed->name }}">
                                                            {{ Str::limit($feed->name, 12) }}
                                                        </span>
                                                        <span class="text-sm font-bold text-gray-900">
                                                            {{ (float)$feed->pivot->quantity_morning }}
                                                            <span class="text-[10px] font-normal text-gray-400">{{ $feed->unit }}</span>
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @if(!$hasMorning)
                                                <li class="py-6 text-center">
                                                    <p class="text-xs italic text-gray-400">Tidak ada pakan</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    {{-- Kolom Sore --}}
                                    <div class="pl-3">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex items-center justify-center rounded-lg w-7 h-7 bg-gradient-to-br from-blue-500 to-indigo-600">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-bold text-blue-600 uppercase">Sore</span>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-200 rounded bg-blue-50">
                                                {{ $record->time_evening ? \Carbon\Carbon::parse($record->time_evening)->format('H:i') : '-' }}
                                            </span>
                                        </div>

                                        <ul class="space-y-2">
                                            @foreach($record->feedTypes as $feed)
                                                @if($feed->pivot->quantity_evening > 0)
                                                    <li class="flex items-center justify-between p-2 transition-colors border border-gray-100 rounded-lg hover:bg-blue-50">
                                                        <span class="text-xs font-medium text-gray-600 truncate" title="{{ $feed->name }}">
                                                            {{ Str::limit($feed->name, 12) }}
                                                        </span>
                                                        <span class="text-sm font-bold text-gray-900">
                                                            {{ (float)$feed->pivot->quantity_evening }}
                                                            <span class="text-[10px] font-normal text-gray-400">{{ $feed->unit }}</span>
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @if(!$hasEvening)
                                                <li class="py-6 text-center">
                                                    <p class="text-xs italic text-gray-400">Tidak ada pakan</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </a>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between px-5 py-4 border-t-2 border-gray-100 bg-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Estimasi Biaya</span>
                                    <span class="text-base font-black text-green-600">
                                        @if($totalCostPerCard > 0)
                                            Rp {{ number_format($totalCostPerCard, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                @if($totalCostPerCard > 0)
                                    <a href="{{ route('profit-loss.create', [
                                        'date' => $record->date->format('Y-m-d'),
                                        'type' => 'expense',
                                        'amount' => $totalCostPerCard,
                                        'category' => 'Pembelian Pakan',
                                        'shelter_id' => $record->shelter_id,
                                        'description' => 'Pakan Harian ' . $record->date->format('d M Y') . ' (' . $record->shelter->name . ')'
                                    ]) }}"
                                    class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-green-700 transition-all duration-200 border-2 border-green-200 rounded-lg bg-green-50 hover:bg-green-100 hover:scale-105"
                                    title="Catat pengeluaran ini ke Buku Kas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Catat
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('feeding-records.edit', $record) }}" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-blue-700 transition-all duration-200 border-2 border-blue-200 rounded-lg bg-blue-50 hover:bg-blue-100 hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('feeding-records.destroy', $record) }}" method="POST" onsubmit="return confirm('Hapus catatan pakan tanggal {{ $record->date->format('d M Y') }}?');" class="inline">
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
                    <div class="col-span-1 md:col-span-2 xl:col-span-3">
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="flex items-center justify-center w-24 h-24 mb-4 rounded-full bg-gradient-to-br from-gray-100 to-amber-100">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-gray-900">
                                @if(request()->filled('partner_id') || request()->filled('start_date') || request()->filled('end_date') || request()->filled('shelter_id'))
                                    Tidak Ada Data Pakan
                                @else
                                    Belum Ada Riwayat Pakan
                                @endif
                            </h3>
                            <p class="mb-6 text-gray-500">
                                @if(request()->filled('partner_id') || request()->filled('start_date') || request()->filled('end_date') || request()->filled('shelter_id'))
                                    Tidak ada catatan pakan yang cocok dengan filter Anda. Coba ubah filter atau reset.
                                @else
                                    Mulai catat pemberian pakan harian untuk monitoring nutrisi domba yang lebih baik.
                                @endif
                            </p>
                            <a href="{{ route('feeding-records.create') }}">
                                <button class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all duration-200 rounded-full shadow-lg bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 hover:shadow-xl hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Catat Pakan Pertama
                                </button>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if($feedingRecords->hasPages())
                <div class="px-6 py-4 bg-white border-2 border-gray-100 shadow-lg rounded-2xl">
                    {{ $feedingRecords->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- CUSTOM STYLES --}}
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
            @apply text-gray-700 bg-white border-2 border-gray-200 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700 hover:scale-105;
        }

        nav[role="navigation"] span[aria-current="page"] {
            @apply text-white bg-gradient-to-r from-amber-600 to-orange-600 border-2 border-amber-600 shadow-lg;
        }

        nav[role="navigation"] span[aria-disabled="true"] {
            @apply text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-not-allowed;
        }

        nav[role="navigation"] svg {
            @apply w-5 h-5;
        }

        /* Card Hover Animation */
        .group:hover .group-hover\:rotate-6 {
            transform: rotate(6deg);
        }

        /* Smooth Transitions */
        * {
            transition-property: transform, box-shadow, background-color, border-color;
        }
    </style>
</x-app-layout>
