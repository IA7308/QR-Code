<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Lapor Sakit / Input Kesehatan: ') . $sheep->tag_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="p-4 mb-6 border border-red-100 rounded-md bg-red-50">
                        <p class="font-bold text-red-800">Melaporkan Kasus Sakit untuk Domba: {{ $sheep->tag_number }}</p>
                        <p class="text-sm text-red-600">Pastikan semua gejala dicentang agar diagnosis akurat.</p>
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

                    <form method="POST" action="{{ route('sheep.health-records.store', $sheep) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 mb-4 md:grid-cols-2">
                            <div>
                                <x-input-label for="record_date" :value="__('Tanggal Kejadian')" />
                                <x-text-input id="record_date" class="block w-full mt-1" type="date" name="record_date" :value="old('record_date', date('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="status" :value="__('Status Penanganan')" />
                                {{-- PENTING: Value menggunakan Inggris agar sesuai standar baru --}}
                                <select id="status" name="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Reported">Baru Dilaporkan (Belum Ditangani)</option>
                                    <option value="Pending Treatment">Menunggu Obat/Dokter</option>
                                    <option value="In Treatment">Sedang Dalam Perawatan</option>
                                    <option value="Completed">Sembuh / Selesai</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="diagnosis" :value="__('Diagnosis / Dugaan Penyakit')" />
                            <x-text-input id="diagnosis" class="block w-full mt-1" type="text" name="diagnosis" :value="old('diagnosis')" placeholder="Contoh: Masuk Angin, Scabies, Diare Akut" required />
                        </div>

                        <div class="mb-4">
                            <span class="block mb-2 text-sm font-medium text-gray-700">Gejala yang Terlihat (Pilih Minimal 1)</span>
                            <div class="grid grid-cols-2 gap-2 p-4 overflow-y-auto border rounded-md max-h-48 bg-gray-50">
                                @forelse($symptoms as $symptom)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}" class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ $symptom->name }}</span>
                                    </label>
                                @empty
                                    <p class="col-span-2 text-sm text-gray-500">Belum ada data gejala master. Silakan input di menu Gejala.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="treatment_details" :value="__('Detail Penanganan / Tindakan')" />
                            <textarea id="treatment_details" name="treatment_details" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jelaskan tindakan yang dilakukan...">{{ old('treatment_details') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="medication_used" :value="__('Obat yang Diberikan (Opsional)')" />
                            <x-text-input id="medication_used" class="block w-full mt-1" type="text" name="medication_used" :value="old('medication_used')" placeholder="Contoh: Vitamin B Kompleks, Antibiotik X" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="photo" :value="__('Foto Kondisi (Opsional)')" />
                            <x-text-input id="photo" class="block w-full p-2 mt-1 border" type="file" name="photo" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sheep.show', $sheep) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900">
                                {{ __('Simpan Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
