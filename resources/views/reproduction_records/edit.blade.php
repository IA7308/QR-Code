<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Update Status Reproduksi: ') . $sheep->tag_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex items-center p-4 mb-6 border border-purple-100 rounded-md bg-purple-50">
                        <div class="p-2 mr-4 bg-purple-200 rounded-full">
                            ♀️
                        </div>
                        <div>
                            <p class="font-bold text-purple-900">Update Siklus</p>
                            <p class="text-sm text-purple-700">Ubah status ke "Delivered" jika domba sudah melahirkan.</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Update --}}
                    <form method="POST" action="{{ route('reproduction-records.update', $reproductionRecord) }}">
                        @csrf
                        @method('PUT')

                        {{-- Pejantan --}}
                        <div class="mb-4">
                            <x-input-label for="male_sheep_id" :value="__('Pejantan (Sire)')" />
                            <select id="male_sheep_id" name="male_sheep_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                @foreach($potential_sires as $sire)
                                    <option value="{{ $sire->id }}" {{ old('male_sheep_id', $reproductionRecord->male_sheep_id) == $sire->id ? 'selected' : '' }}>
                                        {{ $sire->tag_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Kawin --}}
                        <div class="mb-4">
                            <x-input-label for="mating_date" :value="__('Tanggal Kawin')" />
                            <x-text-input id="mating_date" class="block w-full mt-1" type="date" name="mating_date" :value="old('mating_date', $reproductionRecord->mating_date->format('Y-m-d'))" required />
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status Siklus')" />
                            <select id="status" name="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" onchange="toggleDeliveryFields(this.value)">
                                <option value="Planned" {{ $reproductionRecord->status == 'Planned' ? 'selected' : '' }}>Planned (Rencana)</option>
                                <option value="Mated" {{ $reproductionRecord->status == 'Mated' ? 'selected' : '' }}>Mated (Sudah Kawin)</option>
                                <option value="Pregnant" {{ $reproductionRecord->status == 'Pregnant' ? 'selected' : '' }}>Pregnant (Positif Hamil)</option>
                                <option value="Delivered" {{ $reproductionRecord->status == 'Delivered' ? 'selected' : '' }}>Delivered (Sudah Melahirkan)</option>
                                <option value="Failed" {{ $reproductionRecord->status == 'Failed' ? 'selected' : '' }}>Failed (Gagal/Keguguran)</option>
                            </select>
                        </div>

                        {{-- Field Khusus Kelahiran (Hanya muncul jika Delivered) --}}
                        <div id="delivery-fields" class="{{ $reproductionRecord->status == 'Delivered' ? '' : 'hidden' }} p-4 bg-gray-50 rounded-md border mb-4">
                            <h4 class="mb-3 font-bold text-gray-700">Data Kelahiran</h4>

                            <div class="mb-3">
                                <x-input-label for="actual_delivery_date" :value="__('Tanggal Melahirkan')" />
                                <x-text-input id="actual_delivery_date" class="block w-full mt-1" type="date" name="actual_delivery_date" :value="old('actual_delivery_date', $reproductionRecord->actual_delivery_date ? $reproductionRecord->actual_delivery_date->format('Y-m-d') : date('Y-m-d'))" />
                            </div>

                            <div>
                                <x-input-label for="offspring_count" :value="__('Jumlah Anak')" />
                                <x-text-input id="offspring_count" class="block w-full mt-1" type="number" name="offspring_count" :value="old('offspring_count', $reproductionRecord->offspring_count)" placeholder="Contoh: 1" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sheep.show', $sheep) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <x-primary-button class="bg-purple-600 hover:bg-purple-700">
                                {{ __('Update Data') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDeliveryFields(status) {
            const fields = document.getElementById('delivery-fields');
            if (status === 'Delivered') {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
