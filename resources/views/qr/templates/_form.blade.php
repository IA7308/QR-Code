<div class="space-y-5">
    <div>
        <label for="name" class="mb-1 block text-sm font-semibold text-gray-700">Nama template</label>
        <input id="name" name="name" value="{{ old('name', $qrTemplate->name ?? '') }}" required maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Contoh: QR TEMPLATE 1 - Google Review">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="description" class="mb-1 block text-sm font-semibold text-gray-700">Deskripsi</label>
        <textarea id="description" name="description" rows="3" maxlength="2000" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description', $qrTemplate->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="type" class="mb-1 block text-sm font-semibold text-gray-700">Jenis</label>
        @if(($qrTemplate->type ?? null) === 'qr_nfc')
            <input type="hidden" name="type" value="qr_nfc"><input value="QR + NFC" disabled class="w-full rounded-lg border-gray-300 bg-gray-100">
        @else
            <select id="type" name="type" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"><option value="google_review" @selected(old('type', 'google_review') === 'google_review')>Google Review</option></select>
        @endif
    </div>
    <div>
        <label for="status" class="mb-1 block text-sm font-semibold text-gray-700">Status template</label>
        <select id="status" name="status" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            <option value="ACTIVE" @selected(old('status', $qrTemplate->status ?? 'ACTIVE') === 'ACTIVE')>ACTIVE</option>
            <option value="INACTIVE" @selected(old('status', $qrTemplate->status ?? '') === 'INACTIVE')>INACTIVE</option>
        </select>
        @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.qr-templates.index') }}" class="inline-flex min-h-12 items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
        <button type="submit" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="inline-flex min-h-12 w-full items-center justify-center rounded-lg px-8 py-3 text-base font-bold shadow-sm hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 sm:w-auto">SIMPAN TEMPLATE</button>
    </div>
</div>
