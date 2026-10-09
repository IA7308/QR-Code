{{-- Partial Form untuk Create & Edit Gejala --}}

<div class="mb-4">
    <x-input-label for="name" :value="__('Nama Gejala / Penyakit (Wajib)')" />
    <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $symptom->name ?? '')" required autofocus placeholder="Contoh: Diare, Kembung, Kudis" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
    <textarea id="description" name="description" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $symptom->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('symptoms.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
        Batal
    </a>
    <x-primary-button>
        {{ isset($symptom) ? __('Simpan Perubahan') : __('Simpan Gejala') }}
    </x-primary-button>
</div>
