<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Gejala: ') . $symptom->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('symptoms.update', $symptom) }}">
                        @csrf
                        @method('PUT')
                        @include('symptoms._form', ['symptom' => $symptom])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
