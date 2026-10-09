<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftarkan Tempat</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-xl">
                <p class="mb-6 text-sm text-gray-500">Isi nama dan alamat sekarang. URL halaman dan QR tempat akan tetap stabil setelah disimpan.</p>
                <form method="POST" action="{{ route('places.store') }}">
                    @csrf
                    @include('places._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
