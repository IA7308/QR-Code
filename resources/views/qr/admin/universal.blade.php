<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><h2 class="text-2xl font-bold text-gray-800">QR Universal GPS</h2><p class="mt-1 text-sm text-gray-500">Satu QR yang dapat dicetak massal; tiap pemindaian mencari Google Maps terdekat dari GPS pemindai.</p></div>
            <button onclick="window.print()" style="background-color:#15803d;color:#ffffff;" class="rounded-lg px-4 py-2 font-bold">Cetak QR</button>
        </div>
    </x-slot>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div id="qr-print-area" class="rounded-2xl bg-white p-8 text-center shadow-sm">
            <div class="mx-auto max-w-sm">{!! $qrSvg !!}</div>
            <h3 class="mt-5 text-lg font-bold text-gray-800">QR Universal (GPS)</h3>
            <p class="mx-auto mt-2 max-w-xl text-sm text-gray-600">Cetak QR ini sebanyak yang diperlukan. Saat dipindai, browser akan meminta izin GPS lalu membuka Google Review tempat terdekat yang ditemukan.</p>
            <p class="mt-4 break-all rounded-lg bg-gray-50 p-3 text-sm text-gray-700">{{ $url }}</p>
            <p class="mt-4 text-xs text-gray-500">Akses GPS browser memerlukan HTTPS. Pastikan Places API aktif dan radius pencarian diatur di konfigurasi.</p>
        </div>
    </div>
    <style>@media print{body *{visibility:hidden!important}#qr-print-area,#qr-print-area *{visibility:visible!important}#qr-print-area{position:absolute;left:0;top:0;width:100%;padding:20mm!important;box-shadow:none!important}button,header{display:none!important}body,main{background:#fff!important;padding:0!important;margin:0!important}}</style>
</x-app-layout>
