<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div><a href="{{ route('admin.qr-templates.index') }}" class="text-sm font-semibold text-green-700">← Template</a><h2 class="mt-1 text-2xl font-bold text-gray-800">{{ $qrTemplate->name }}</h2></div>
            <div class="flex gap-2">
                <a href="{{ route('admin.qr-units.index', ['template' => $qrTemplate->id]) }}" class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700">Cari semua unit</a>
                <a href="{{ route('admin.qr-templates.edit', $qrTemplate) }}" class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700">Edit template</a>
                <form method="POST" action="{{ route('admin.qr-templates.destroy', $qrTemplate) }}" onsubmit="return confirm('Hapus template beserta seluruh unit QR, destination, dan riwayat scan?')">@csrf @method('DELETE')<button style="background-color:#b91c1c;color:#ffffff;" class="rounded-lg px-4 py-2 text-sm font-bold">Hapus template</button></form>
            </div>
        </div>
    </x-slot>
    <div class="py-8"><div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
        <div class="grid gap-5 md:grid-cols-2">
            @if($qrTemplate->shared_mode)
            @php($sharedUnit = $units->first())
            <section class="rounded-xl bg-white p-6 shadow-sm md:col-span-2">
                <div class="flex flex-wrap items-start justify-between gap-4"><div><h3 class="text-lg font-bold">Satu QR bersama</h3><p class="mt-1 text-sm text-gray-600">Semua orang memindai URL yang sama. Di halaman scan, mereka memilih satu dari lokasi aktif.</p></div>@if($sharedUnit)<a href="{{ route('admin.qr-templates.print', $qrTemplate) }}" style="background-color:#15803d;color:#ffffff;" class="rounded-lg px-4 py-2 text-sm font-bold">Cetak QR</a>@endif</div>
                @if($sharedUnit)
                    <div class="mt-4 grid gap-5 md:grid-cols-2">
                        <div class="rounded-xl border p-4 text-center"><div class="mx-auto w-fit">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(220)->margin(1)->generate(route('qr.public.show', $sharedUnit->token)) !!}</div><p class="mt-2 break-all text-xs text-gray-500">{{ route('qr.public.show', $sharedUnit->token) }}</p><a href="{{ route('qr.public.show', $sharedUnit->token) }}" target="_blank" class="mt-2 inline-block text-sm font-semibold text-green-700 underline">Buka halaman scan</a></div>
                        <div><h4 class="font-semibold">Lokasi &amp; kode aktivasi otomatis</h4><p class="mt-1 text-xs text-gray-500">Kode dibuat saat lokasi ditambahkan. Tidak perlu dimasukkan manual.</p><div class="mt-3 max-h-72 space-y-2 overflow-y-auto">@forelse($sharedUnit->destinations as $location)<div class="rounded-lg bg-gray-50 p-3"><div class="font-semibold">{{ $location->place_name }}</div><div class="text-sm text-gray-600">{{ $location->place_address }}</div><div class="mt-1 font-mono text-xs text-gray-500">{{ $location->activation_code }}</div></div>@empty<p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-800">Belum ada lokasi. Buka halaman scan lalu pilih “Tambah lokasi baru”.</p>@endforelse</div></div>
                    </div>
                @endif
            </section>
            @else
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <p class="text-sm text-gray-600">{{ $qrTemplate->description ?: 'Template untuk produk Dynamic QR Review.' }}</p>
                <p class="mt-3 text-sm">Status: <strong>{{ $qrTemplate->status }}</strong></p>
                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach(['EMPTY' => 'Kosong', 'PENDING' => 'Diproses', 'ACTIVE' => 'Aktif', 'DISABLED' => 'Nonaktif'] as $status => $label)
                        <div class="rounded-lg bg-gray-50 p-3"><div class="text-xs text-gray-500">{{ $label }}</div><div class="mt-1 text-xl font-bold">{{ number_format($unitStats[$status] ?? 0) }}</div></div>
                    @endforeach
                </div>
            </section>
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold">Produksi massal</h3>
                <p class="mt-1 text-sm text-gray-500">Setiap unit mendapat unit code dan token acak yang berbeda. QR satu unit tidak berbagi URL dengan unit lain.</p>
                @if(($unitStats['EMPTY'] ?? 0) + ($unitStats['PENDING'] ?? 0) + ($unitStats['ACTIVE'] ?? 0) + ($unitStats['DISABLED'] ?? 0) > 0)
                    <a href="{{ route('admin.qr-templates.print', $qrTemplate) }}" style="background-color:#1f2937;color:#ffffff;border:1px solid #111827;" class="mt-4 inline-flex w-full items-center justify-center rounded-lg px-5 py-3 font-bold shadow-sm hover:brightness-90 sm:w-auto">Cetak QR Template</a>
                @endif
                @if($qrTemplate->status === 'ACTIVE')
                    <form method="POST" action="{{ route('admin.qr-templates.produce', $qrTemplate) }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:flex-wrap">@csrf
                        <div class="flex-1"><label for="quantity" class="sr-only">Jumlah unit</label><input id="quantity" name="quantity" type="number" min="1" max="5000" required value="{{ old('quantity', 10) }}" class="w-full rounded-lg border-gray-300" placeholder="Jumlah unit">@error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div class="flex-1"><label for="qr_client_id" class="sr-only">Klien pemilik unit</label><select id="qr_client_id" name="qr_client_id" class="w-full rounded-lg border-gray-300"><option value="">Stok umum (belum ditetapkan)</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('qr_client_id') == $client->id)>{{ $client->company_name ?: $client->user->name }} · {{ $client->user->email }}</option>@endforeach</select>@error('qr_client_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <button type="submit" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="w-full rounded-lg px-5 py-3 font-bold shadow-sm hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 sm:w-auto">Generate &amp; cetak batch</button>
                    </form>
                @else
                    <p class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">Aktifkan template untuk memproduksi unit baru.</p>
                @endif
            </section>
            @endif
        </div>
        <section class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b p-5"><h3 class="font-bold">Unit terbaru</h3></div>
            <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Nama unit / tempat</th><th class="px-5 py-3">Token</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y">
                @forelse($units as $unit)<tr><td class="px-5 py-3"><div class="font-semibold">{{ $unit->currentDestination?->place_name ?: $unit->unit_code }}</div>@if($unit->currentDestination)<div class="text-xs font-mono text-gray-500">{{ $unit->unit_code }}</div>@endif</td><td class="px-5 py-3 font-mono text-xs">{{ $unit->token }}</td><td class="px-5 py-3">{{ $unit->status }}</td><td class="px-5 py-3 text-right"><a class="font-semibold text-green-700" href="{{ route('admin.qr-units.show', $unit) }}">Detail</a></td></tr>
                @empty<tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">Belum ada unit. Masukkan jumlah lalu generate batch.</td></tr>@endforelse
            </tbody></table></div>
            <div class="border-t p-4">{{ $units->links() }}</div>
        </section>
    </div></div>
</x-app-layout>
