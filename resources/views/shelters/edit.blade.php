<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Kandang: ') . $shelter->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-md">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('shelters.update', $shelter) }}">
                        @csrf
                        @method('PUT') {{-- Gunakan PUT untuk update --}}

                        {{-- Memanggil Partial Form --}}
                        @include('shelters._form', ['shelter' => $shelter])

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
