<x-app-layout>
    <x-slot name="header"><div><h2 class="text-2xl font-bold text-gray-800">Bantuan Dynamic QR</h2><p class="mt-1 text-sm text-gray-500">Panduan singkat pengelolaan QR unit dan destination review.</p></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-4xl space-y-5 px-4 sm:px-6 lg:px-8">
        <section class="rounded-xl bg-white p-6 shadow-sm"><h3 class="text-lg font-bold">1. Buat template</h3><p class="mt-2 text-sm leading-6 text-gray-600">Template adalah jenis produk/desain. Buka Kelola Template, pilih Buat Template, lalu simpan nama template.</p></section>
        <section class="rounded-xl bg-white p-6 shadow-sm"><h3 class="text-lg font-bold">2. Produksi dan cetak QR</h3><p class="mt-2 text-sm leading-6 text-gray-600">Di detail template, masukkan jumlah unit lalu pilih Generate & cetak batch. Sistem membuat token dan URL unik untuk setiap unit. Cetak QR batch atau buka detail unit untuk mencetak satu QR.</p></section>
        <section class="rounded-xl bg-white p-6 shadow-sm"><h3 class="text-lg font-bold">3. Aktivasi oleh customer</h3><p class="mt-2 text-sm leading-6 text-gray-600">Saat QR kosong dipindai, customer mengisi nama dan alamat tempat. Sistem mencari Google Place, menyimpan URL review, lalu QR berikutnya langsung mengarah ke halaman review.</p></section>
        <section class="rounded-xl bg-white p-6 shadow-sm"><h3 class="text-lg font-bold">Status unit</h3><p class="mt-2 text-sm leading-6 text-gray-600"><strong>EMPTY</strong>: siap diisi. <strong>PENDING</strong>: sedang mencari tempat. <strong>ACTIVE</strong>: destination tersimpan dan akan diarahkan ke review. <strong>DISABLED</strong>: QR dinonaktifkan.</p></section>
        <a href="{{ route('admin.qr-templates.index') }}" style="background-color:#15803d;color:#ffffff;" class="inline-flex rounded-lg px-5 py-3 font-bold">Buka Kelola Template</a>
    </div></div>
</x-app-layout>
