@if (($place->qr_sale_status ?? null) === 'pending_payment')
<div class="space-y-5">
    <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-900">
        QR Template ini tetap menggunakan URL yang sama. Isi data klien dan tempat, lalu konfirmasi pembayaran dari halaman detail untuk mengaktifkan unduhan QR.
    </div>
    <div>
        <label for="buyer_name" class="mb-1 block text-sm font-semibold text-gray-700">Nama klien</label>
        <input id="buyer_name" name="buyer_name" value="{{ old('buyer_name', $place->buyer_name) }}" required maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
        @error('buyer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="buyer_phone" class="mb-1 block text-sm font-semibold text-gray-700">Nomor WhatsApp / telepon klien</label>
        <input id="buyer_phone" name="buyer_phone" value="{{ old('buyer_phone', $place->buyer_phone) }}" required maxlength="50" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
        @error('buyer_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="sale_price" class="mb-1 block text-sm font-semibold text-gray-700">Harga penjualan (Rp)</label>
        <input id="sale_price" name="sale_price" type="number" min="1" step="1" value="{{ old('sale_price', $place->sale_price) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
        @error('sale_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="name" class="mb-1 block text-sm font-semibold text-gray-700">Nama tempat</label>
        <input id="name" name="name" value="{{ old('name', $place->name) }}" required maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Contoh: Solaria - Metro Indah Mall Bandung">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="address_or_maps_link" class="mb-1 block text-sm font-semibold text-gray-700">Alamat atau link Google Maps</label>
        <input id="address_or_maps_link" name="address_or_maps_link" value="{{ old('address_or_maps_link', $place->address) }}" required maxlength="2048" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Alamat lengkap atau link Maps /maps/place/…">
        @error('address_or_maps_link')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-gray-500">Sistem akan mencari tempat melalui Google Maps dan menyimpan tautan review-nya.</p>
    </div>
    <div class="flex justify-end gap-3 pt-2">
        <a href="{{ route('places.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Kembali</a>
        <button type="submit" class="rounded-lg bg-green-600 px-5 py-2 text-sm font-bold text-white hover:bg-green-700">Simpan data klien & tempat</button>
    </div>
</div>
@else
<div class="space-y-5">
    <div>
        <label for="name" class="block mb-1 text-sm font-semibold text-gray-700">Nama tempat <span class="text-red-500">*</span></label>
        <input id="name" name="name" value="{{ old('name', $place->name ?? '') }}" required maxlength="255"
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Contoh: JAS Farm Sinergi">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="address" class="block mb-1 text-sm font-semibold text-gray-700">Alamat</label>
        <textarea id="address" name="address" rows="2" maxlength="255" required
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Alamat lengkap tempat">{{ old('address', $place->address ?? '') }}</textarea>
        @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="google_review_url" class="block mb-1 text-sm font-semibold text-gray-700">URL Google Review</label>
        <input id="google_review_url" name="google_review_url" type="url" value="{{ old('google_review_url', $place->google_review_url ?? '') }}" maxlength="2048"
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="https://g.page/r/.../review">
        @error('google_review_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-gray-500">QR tetap memakai URL yang sama; halaman QR akan mengalihkan pemindai ke tautan review ini.</p>
    </div>

    <div class="p-4 text-sm text-blue-800 bg-blue-50 rounded-lg">
        QR tetap menuju halaman tempat ini. Mengubah nama atau alamat tidak mengubah QR yang sudah dicetak. Tautan Google Maps dibuat dari nama dan alamat; QR tidak memerlukan API key.
    </div>

    <div class="flex justify-end gap-3 pt-2">
        <a href="{{ route('places.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
        <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">Simpan tempat</button>
    </div>
</div>
@endif
