<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Buat QR Template</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-2xl space-y-4 px-4 sm:px-6 lg:px-8"><div class="rounded-xl bg-blue-50 p-4 text-sm text-blue-900">Saat template disimpan, sistem membuat satu URL QR bersama. Anda dapat menambahkan banyak lokasi; kode aktivasi untuk setiap lokasi dibuat otomatis. Saat QR dipindai, pengguna memilih lokasi tujuan.</div><div class="rounded-xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.qr-templates.store') }}">@csrf @include('qr.templates._form', ['qrTemplate' => null])</form>
    </div></div></div>
</x-app-layout>
