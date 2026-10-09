<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Catat Reproduksi / Kawin: ') . $sheep->tag_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Info Induk --}}
                    <div class="flex items-center p-4 mb-6 border border-purple-100 rounded-md bg-purple-50">
                        <div class="p-2 mr-4 bg-purple-200 rounded-full">
                            ♀️
                        </div>
                        <div>
                            <p class="font-bold text-purple-900">Induk Betina (Dam)</p>
                            <p class="text-purple-700">Eartag: {{ $sheep->tag_number }}</p>
                        </div>
                    </div>

                    {{-- PERBAIKAN: Gunakan nama rute yang baru 'sheep.reproduction-records.store' --}}
                    <form method="POST" action="{{ route('sheep.reproduction-records.store', $sheep) }}">
                        @csrf

                        {{-- Pilih Pejantan --}}
                        <div class="mb-4">
                            <x-input-label for="male_sheep_id" :value="__('Pejantan (Sire) - Wajib')" />
                            <select id="male_sheep_id" name="male_sheep_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Pejantan --</option>
                                @foreach($potential_sires as $sire)
                                    <option value="{{ $sire->id }}">{{ $sire->tag_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('male_sheep_id')" class="mt-2" />
                        </div>

                        {{-- Tanggal Kawin --}}
                        <div class="mb-4">
                            <x-input-label for="mating_date" :value="__('Tanggal Kawin')" />
                            <x-text-input id="mating_date" class="block w-full mt-1" type="date" name="mating_date" :value="old('mating_date', date('Y-m-d'))" required />
                            <p class="mt-1 text-xs text-gray-500">*Estimasi kelahiran akan dihitung otomatis (+147 hari).</p>
                            <x-input-error :messages="$errors->get('mating_date')" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status Siklus')" />
                            <select id="status" name="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Mated">Mated (Sudah Kawin)</option>
                                <option value="Pregnant">Pregnant (Positif Hamil)</option>
                                <option value="Planned">Planned (Rencana)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sheep.show', $sheep) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="bg-purple-600 hover:bg-purple-700">
                                {{ __('Simpan Data') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
