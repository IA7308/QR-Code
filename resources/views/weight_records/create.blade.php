<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Input Timbangan: ') . $sheep->tag_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Info Singkat Domba --}}
                    <div class="p-4 mb-6 border border-gray-200 rounded-md bg-gray-50">
                        <p><strong>Eartag:</strong> {{ $sheep->tag_number }}</p>
                        <p><strong>Kandang:</strong> {{ $sheep->shelter->name ?? '-' }}</p>
                    </div>

                    {{-- (1) PERBARUI RUTE ACTION DI SINI --}}
                    <form method="POST" action="{{ route('sheep.weights.store', $sheep) }}">
                        @csrf

                        {{-- Tanggal Timbang --}}
                        <div class="mb-4">
                            <x-input-label for="weighing_date" :value="__('Tanggal Timbang')" />
                            <x-text-input id="weighing_date" class="block w-full mt-1" type="date" name="weighing_date" :value="old('weighing_date', date('Y-m-d'))" required autofocus />
                            <x-input-error :messages="$errors->get('weighing_date')" class="mt-2" />
                        </div>

                        {{-- Berat (KG) --}}
                        <div class="mb-4">
                            <x-input-label for="weight" :value="__('Berat (KG)')" />
                            <x-text-input id="weight" class="block w-full mt-1" type="number" step="0.01" name="weight" :value="old('weight')" required placeholder="0.00" />
                            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>

                        {{-- Catatan --}}
                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                            <textarea id="notes" name="notes" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sheep.show', $sheep) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Data Timbangan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
