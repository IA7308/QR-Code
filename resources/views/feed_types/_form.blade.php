{{-- Partial Form untuk Create & Edit Jenis Pakan --}}

<div class="grid grid-cols-1 gap-6 mb-4 md:grid-cols-2">
    <div>
        <x-input-label for="name" :value="__('Nama Pakan (Wajib)')" />
        <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $feedType->name ?? '')" required autofocus placeholder="Contoh: Rumput Gajah, Konsentrat A" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="unit" :value="__('Satuan (Wajib)')" />
        <x-text-input id="unit" class="block w-full mt-1" type="text" name="unit" :value="old('unit', $feedType->unit ?? '')" required placeholder="Contoh: kg, ikat, karung" />
        <p class="mt-1 text-xs text-gray-500">Satuan yang digunakan saat menimbang/memberi pakan.</p>
        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
    </div>
</div>

{{-- Tambahkan Input Harga --}}
<div class="mb-4">
    <x-input-label for="price_per_unit" :value="__('Harga Beli per Satuan (IDR)')" />
    <x-text-input id="price_per_unit" class="block w-full mt-1" type="number" step="0.01" name="price_per_unit" :value="old('price_per_unit', $feedType->price_per_unit ?? '')" required min="0" placeholder="Contoh: 5000 (untuk 1 KG/Ikat/Satuan)" />
    <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="description" :value="__('Deskripsi / Kandungan Nutrisi (Opsional)')" />
    <textarea id="description" name="description" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $feedType->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('feed-types.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
        Batal
    </a>
    <x-primary-button>
        {{ isset($feedType) ? __('Simpan Perubahan') : __('Simpan Jenis Pakan') }}
    </x-primary-button>
</div>
