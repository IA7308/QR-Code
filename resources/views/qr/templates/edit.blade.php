<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Edit QR Template</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8"><div class="rounded-xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.qr-templates.update', $qrTemplate) }}">@csrf @method('PUT') @include('qr.templates._form')</form>
    </div></div></div>
</x-app-layout>
