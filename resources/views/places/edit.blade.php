<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $place->qr_sale_status === 'pending_payment' ? 'Data Penjualan QR Template' : ($place->name ? 'Edit Tempat: ' . $place->name : 'Isi Tempat dari QR Kosong') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-xl">
                <form method="POST" action="{{ route('places.update', $place) }}">
                    @csrf
                    @method('PUT')
                    @include('places._form', ['place' => $place])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
