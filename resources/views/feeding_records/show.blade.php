<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-amber-400 to-orange-600">
                    <span class="text-2xl">🍽️</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-gray-800">
                        {{ __('Detail Pemberian Pakan') }}
                    </h2>
                    <p class="text-sm font-medium text-amber-600">Informasi lengkap catatan pakan harian</p>
                </div>
            </div>
            <a href="{{ route('feeding-records.index') }}">
                <button class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-700 transition-all duration-200 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:scale-105 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ __('Kembali') }}
                </button>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-amber-50">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="overflow-hidden transition-shadow bg-white shadow-lg hover:shadow-2xl rounded-2xl">

                {{-- HEADER DETAIL --}}
                <div class="flex flex-col gap-6 p-6 border-b-2 border-gray-100 md:p-8 md:flex-row md:justify-between md:items-center bg-gradient-to-r from-gray-50 to-amber-50">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-16 h-16 shadow-lg rounded-xl bg-gradient-to-br from-amber-400 to-orange-600">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-900">
                                {{ $feedingRecord->date->format('l, d F Y') }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1 text-sm text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-semibold">{{ $feedingRecord->user->name }}</span>
                                <span class="text-gray-400">•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $feedingRecord->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('feeding-records.edit', $feedingRecord) }}">
                            <button class="flex items-center gap-2 px-5 py-3 text-sm font-bold text-white transition-all duration-200 shadow-lg rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                {{ __('Edit') }}
                            </button>
                        </a>
                        <form action="{{ route('feeding-records.destroy', $feedingRecord) }}" method="POST" onsubmit="return confirm('Hapus catatan ini? Data tidak dapat dikembalikan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2 px-5 py-3 text-sm font-bold text-white transition-all duration-200 bg-red-600 shadow-lg rounded-xl hover:bg-red-700 hover:shadow-xl hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                {{ __('Hapus') }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-6 md:p-8">

                    {{-- INFO UTAMA (KPI CARDS) --}}
                    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">

                        {{-- Lokasi Kandang --}}
                        <div class="relative overflow-hidden transition-all duration-300 transform shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                            <div class="absolute top-0 right-0 w-24 h-24 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-green-400 to-emerald-600"></div>
                            <div class="relative p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-green-500 to-emerald-600">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    </div>
                                </div>
                                <span class="block text-xs font-bold tracking-wide text-green-700 uppercase">Lokasi Kandang</span>
                                <span class="block mt-2 text-2xl font-black text-green-900">{{ $feedingRecord->shelter->name }}</span>
                            </div>
                        </div>

                        {{-- Waktu Pagi --}}
                        <div class="relative overflow-hidden transition-all duration-300 transform shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                            <div class="absolute top-0 right-0 w-24 h-24 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-amber-400 to-orange-600"></div>
                            <div class="relative p-6 bg-gradient-to-br from-amber-50 to-orange-50">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <div class="flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-amber-700 border-2 border-amber-200 rounded-lg bg-amber-100">
                                        <span>☀️ Pagi</span>
                                    </div>
                                </div>
                                <span class="block text-xs font-bold tracking-wide uppercase text-amber-700">Waktu Pemberian</span>
                                <span class="block mt-2 text-3xl font-black text-amber-900">
                                    {{ $feedingRecord->time_morning ? \Carbon\Carbon::parse($feedingRecord->time_morning)->format('H:i') : '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Waktu Sore --}}
                        <div class="relative overflow-hidden transition-all duration-300 transform shadow-lg hover:scale-105 hover:shadow-2xl rounded-2xl">
                            <div class="absolute top-0 right-0 w-24 h-24 transition-transform duration-300 transform translate-x-8 -translate-y-8 rounded-full opacity-10 bg-gradient-to-br from-blue-400 to-indigo-600"></div>
                            <div class="relative p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center justify-center w-12 h-12 shadow-lg rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                    </div>
                                    <div class="flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-blue-700 border-2 border-blue-200 rounded-lg bg-blue-100">
                                        <span>🌙 Sore</span>
                                    </div>
                                </div>
                                <span class="block text-xs font-bold tracking-wide text-blue-700 uppercase">Waktu Pemberian</span>
                                <span class="block mt-2 text-3xl font-black text-blue-900">
                                    {{ $feedingRecord->time_evening ? \Carbon\Carbon::parse($feedingRecord->time_evening)->format('H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- TABEL DETAIL PAKAN --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-md bg-gradient-to-br from-green-500 to-emerald-600">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">Rincian Jenis Pakan</h4>
                                <p class="text-sm text-green-600">Detail porsi dan biaya per jenis pakan</p>
                            </div>
                        </div>

                        <div class="overflow-hidden border-2 border-gray-100 rounded-2xl">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-gray-100 to-green-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-700 uppercase">Jenis Pakan</th>
                                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-700 uppercase">
                                                <div class="flex items-center justify-center gap-1">
                                                    <span>☀️</span>
                                                    <span>Porsi Pagi</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-700 uppercase">
                                                <div class="flex items-center justify-center gap-1">
                                                    <span>🌙</span>
                                                    <span>Porsi Sore</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-700 uppercase bg-green-50">Total Qty</th>
                                            <th class="px-6 py-4 text-xs font-bold tracking-wider text-right text-gray-700 uppercase">Estimasi Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @php $grandTotalCost = 0; @endphp
                                        @foreach($feedingRecord->feedTypes as $feed)
                                            @php
                                                $morning = $feed->pivot->quantity_morning ?? 0;
                                                $evening = $feed->pivot->quantity_evening ?? 0;
                                                $totalQty = $morning + $evening;
                                                $cost = $totalQty * ($feed->price_per_unit ?? 0);
                                                $grandTotalCost += $cost;
                                            @endphp
                                            <tr class="transition-colors hover:bg-green-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100">
                                                            <span class="text-xl">🌾</span>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-bold text-gray-900">{{ $feed->name }}</p>
                                                            <p class="text-xs text-gray-500">Satuan: <span class="font-semibold">{{ $feed->unit }}</span></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-bold text-amber-700 border-2 border-amber-200 rounded-lg bg-amber-50">
                                                        {{ number_format($morning, 2) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-bold text-blue-700 border-2 border-blue-200 rounded-lg bg-blue-50">
                                                        {{ number_format($evening, 2) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center bg-green-50 whitespace-nowrap">
                                                    <span class="text-base font-black text-green-600">
                                                        {{ number_format($totalQty, 2) }} <span class="text-xs font-normal text-gray-500">{{ $feed->unit }}</span>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                                    @if($feed->price_per_unit)
                                                        <p class="text-base font-bold text-gray-900">Rp {{ number_format($cost, 0, ',', '.') }}</p>
                                                        <p class="text-xs text-gray-400">@ Rp {{ number_format($feed->price_per_unit, 0, ',', '.') }}</p>
                                                    @else
                                                        <span class="italic text-gray-400">Harga belum diset</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if($grandTotalCost > 0)
                                        <tfoot class="bg-gradient-to-r from-green-100 to-emerald-100">
                                            <tr>
                                                <td colspan="4" class="px-6 py-5 text-lg font-bold text-right text-gray-800">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <span>Total Estimasi Biaya Harian:</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5 text-right">
                                                    <span class="text-2xl font-black text-green-700">
                                                        Rp {{ number_format($grandTotalCost, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
