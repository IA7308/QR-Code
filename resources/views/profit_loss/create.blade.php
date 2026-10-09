<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Catat Transaksi Keuangan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Perhatikan x-data mengambil default dari request jika ada --}}
                    <form method="POST" action="{{ route('profit-loss.store') }}" x-data="{ type: '{{ old('type', request('type', 'expense')) }}' }">
                        @csrf

                        {{-- 1. PILIH JENIS TRANSAKSI --}}
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-center text-gray-700">Apa jenis transaksinya?</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="expense" class="sr-only peer" x-model="type">
                                    <div class="p-4 text-center transition-all border-2 rounded-lg peer-checked:border-red-600 peer-checked:bg-red-50 hover:bg-gray-50">
                                        <div class="mb-2 text-3xl">💸</div>
                                        <div class="font-bold text-red-700">Uang Keluar</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="income" class="sr-only peer" x-model="type">
                                    <div class="p-4 text-center transition-all border-2 rounded-lg peer-checked:border-green-600 peer-checked:bg-green-50 hover:bg-gray-50">
                                        <div class="mb-2 text-3xl">💰</div>
                                        <div class="font-bold text-green-700">Uang Masuk</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- 2. TANGGAL & JUMLAH --}}
                        <div class="grid grid-cols-1 gap-6 mb-4 md:grid-cols-2">
                            <div>
                                <x-input-label for="date" :value="__('Tanggal Kejadian')" />
                                {{-- PERBAIKAN: Gunakan request('date') untuk pre-fill tanggal --}}
                                <x-text-input id="date" class="block w-full mt-1" type="date" name="date" :value="old('date', request('date', date('Y-m-d')))" required />
                            </div>
                            <div>
                                <x-input-label for="amount" :value="__('Jumlah Uang (Rp)')" />
                                {{-- PERBAIKAN: Gunakan request('amount') untuk pre-fill jumlah --}}
                                <x-text-input id="amount" class="block w-full mt-1 text-lg font-bold" type="number" step="0.01" name="amount" :value="old('amount', request('amount'))" required placeholder="0" />
                            </div>
                        </div>

                        {{-- 3. UNTUK APA? --}}
                        <div class="mb-4">
                            <x-input-label for="category" :value="__('Keperluan / Sumber Dana')" />
                            {{-- PERBAIKAN: Gunakan request('category') --}}
                            <x-text-input id="category" class="block w-full mt-1" type="text" name="category" :value="old('category', request('category'))" placeholder="Contoh: Beli Pakan" required list="category-list" />

                            <datalist id="category-list">
                                <option value="Pembelian Pakan">
                                <option value="Obat-obatan">
                                <option value="Gaji Pegawai">
                                <option value="Penjualan Domba">
                                <option value="Operasional Kandang">
                            </datalist>
                        </div>

                        {{-- 4. HUBUNGKAN --}}
                        <div class="p-4 mb-4 border rounded bg-gray-50">
                            <div class="mb-2 text-sm font-bold text-gray-700">Hubungkan (Jika Perlu)</div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-input-label for="sheep_id" :value="__('Untuk Domba Mana?')" />
                                    <select id="sheep_id" name="sheep_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Umum (Semua) --</option>
                                        @foreach($sheeps as $s)
                                            {{-- PERBAIKAN: Cek request('sheep_id') --}}
                                            <option value="{{ $s->id }}" {{ old('sheep_id', request('sheep_id')) == $s->id ? 'selected' : '' }}>{{ $s->tag_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="shelter_id" :value="__('Untuk Kandang Mana?')" />
                                    <select id="shelter_id" name="shelter_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Umum (Semua) --</option>
                                        @foreach($shelters as $s)
                                            {{-- PERBAIKAN: Cek request('shelter_id') --}}
                                            <option value="{{ $s->id }}" {{ old('shelter_id', request('shelter_id')) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- 5. CATATAN --}}
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Catatan Tambahan (Opsional)')" />
                            {{-- PERBAIKAN: Gunakan request('description') --}}
                            <textarea id="description" name="description" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Catatan kecil...">{{ old('description', request('description')) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('profit-loss.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="justify-center w-full py-3 text-lg sm:w-auto">
                                {{ __('Simpan Catatan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
