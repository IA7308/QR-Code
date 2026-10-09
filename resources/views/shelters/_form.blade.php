{{-- Partial ini digunakan oleh create.blade.php dan edit.blade.php --}}
{{-- Tanda garis bawah (_) pada nama file adalah konvensi untuk menandakan partial view --}}

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    {{-- Nama Kandang --}}
    <div>
        <x-input-label for="name" :value="__('Nama Kandang (Wajib)')" />
        {{-- Kita gunakan old() untuk repopulate data saat validasi gagal --}}
        {{-- $shelter->name ?? '' artinya: jika ada data shelter (saat edit), tampilkan namanya. Jika tidak (saat create), kosongkan. --}}
        <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $shelter->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    {{-- Kapasitas --}}
    <div>
        <x-input-label for="capacity" :value="__('Kapasitas Domba (Wajib)')" />
        <x-text-input id="capacity" class="block w-full mt-1" type="number" name="capacity" :value="old('capacity', $shelter->capacity ?? '')" required min="1" />
        <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
    </div>
</div>

{{-- Deskripsi --}}
<div class="mt-4">
    <x-input-label for="description" :value="__('Deskripsi/Catatan Tambahan')" />
    <textarea id="description" name="description" rows="3"
              class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $shelter->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

{{-- Tombol Submit --}}
<div class="flex items-center justify-end mt-6">
    {{-- Tombol Batal kembali ke index --}}
    <a href="{{ route('shelters.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
        Batal
    </a>

    {{-- Teks tombol berubah tergantung apakah ini Create atau Edit --}}
    <x-primary-button>
        {{ isset($shelter) ? __('Simpan Perubahan') : __('Buat Kandang') }}
    </x-primary-button>
</div>
