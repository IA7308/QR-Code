<x-app-layout>
    <x-slot name="header">
        <div><h2 class="text-2xl font-bold text-gray-800">QR + NFC</h2><p class="mt-1 text-sm text-gray-500">Setiap produk punya URL unik yang sama untuk QR dan tag NFC.</p></div>
    </x-slot>
    <div class="py-8"><div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
        <div class="grid gap-6 lg:grid-cols-5">
            <section class="rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="text-lg font-bold">Buat batch QR + NFC</h3>
                <p class="mt-1 text-sm text-gray-600">Setiap unit mendapatkan QR dan URL NFC unik. URL ditampilkan di lembar cetak dan CSV untuk ditulis ke tag NFC.</p>
                <form method="POST" action="{{ route('admin.qr-nfc.store') }}" class="mt-5 space-y-4">@csrf
                    <div><label for="name" class="mb-1 block text-sm font-semibold">Nama produk / batch</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-lg border-gray-300" placeholder="Contoh: QR + NFC Review">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label for="description" class="mb-1 block text-sm font-semibold">Deskripsi</label><textarea id="description" name="description" rows="2" maxlength="2000" class="w-full rounded-lg border-gray-300" placeholder="Opsional">{{ old('description') }}</textarea></div>
                    <div><label for="quantity" class="mb-1 block text-sm font-semibold">Jumlah unit</label><input id="quantity" type="number" name="quantity" min="1" max="5000" value="{{ old('quantity', 10) }}" required class="w-full rounded-lg border-gray-300">@error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label for="qr_client_id" class="mb-1 block text-sm font-semibold">Alokasikan ke klien</label><select id="qr_client_id" name="qr_client_id" class="w-full rounded-lg border-gray-300"><option value="">Stok umum</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('qr_client_id') == $client->id)>{{ $client->company_name ?: $client->user->name }} · sisa {{ max(0, $client->subscriptions->sortByDesc('ends_at')->first()->unit_limit - $client->units()->count()) }}</option>@endforeach</select>@error('qr_client_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <button type="submit" style="background-color:#15803d;color:#ffffff;" class="w-full rounded-lg px-5 py-3 font-bold">Generate QR + NFC</button>
                </form>
            </section>
            <section class="overflow-hidden rounded-xl bg-white shadow-sm lg:col-span-3">
                <div class="border-b p-5"><h3 class="font-bold">Batch QR + NFC</h3><p class="mt-1 text-sm text-gray-500">Cetak QR dan tulis URL unit yang sama ke setiap NFC.</p></div>
                <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Produk</th><th class="px-5 py-3">Jumlah</th><th class="px-5 py-3">Dibuat</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y">
                    @forelse($templates as $template)<tr><td class="px-5 py-4"><div class="font-semibold">{{ $template->name }}</div><div class="text-xs text-gray-500">QR + NFC</div></td><td class="px-5 py-4">{{ number_format($template->units_count) }} unit</td><td class="px-5 py-4">{{ $template->created_at->format('d/m/Y') }}</td><td class="px-5 py-4 text-right"><a href="{{ route('admin.qr-templates.show', $template) }}" class="font-semibold text-green-700">Detail</a>@php($batch = $template->units()->value('production_batch'))@if($batch)<a href="{{ route('admin.qr-units.batch.print', $batch) }}" class="ml-3 font-semibold text-green-700">Cetak / CSV</a>@endif<form method="POST" action="{{ route('admin.qr-nfc.destroy', $template) }}" class="mt-2" onsubmit="return confirm('Hapus batch QR + NFC beserta semua unit, tujuan Google Review, dan riwayat scan? Tindakan ini tidak dapat dibatalkan.')">@csrf @method('DELETE')<button type="submit" style="background-color:#b91c1c;color:#ffffff;" class="rounded-lg px-3 py-2 text-xs font-bold">Hapus batch</button></form></td></tr>
                    @empty<tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">Belum ada batch QR + NFC.</td></tr>@endforelse
                </tbody></table></div><div class="border-t p-4">{{ $templates->links() }}</div>
            </section>
        </div>
    </div></div>
</x-app-layout>
