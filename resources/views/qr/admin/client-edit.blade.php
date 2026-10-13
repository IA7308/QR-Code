<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-bold text-gray-800">Edit Klien QR</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">
        @if($errors->any())<div class="rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('admin.qr-clients.update', $qrClient) }}" class="space-y-5 rounded-xl bg-white p-6 shadow-sm">@csrf @method('PATCH')
            <div><label for="name" class="mb-1 block text-sm font-semibold">Nama</label><input id="name" name="name" value="{{ old('name', $qrClient->user->name) }}" required maxlength="255" class="w-full rounded-lg border-gray-300"></div>
            <div><label for="company_name" class="mb-1 block text-sm font-semibold">Nama usaha</label><input id="company_name" name="company_name" value="{{ old('company_name', $qrClient->company_name) }}" maxlength="255" class="w-full rounded-lg border-gray-300"></div>
            <div><label for="username" class="mb-1 block text-sm font-semibold">Username</label><input id="username" name="username" value="{{ old('username', $qrClient->user->username) }}" required maxlength="50" class="w-full rounded-lg border-gray-300"></div>
            <div><label for="email" class="mb-1 block text-sm font-semibold">Email</label><input id="email" name="email" type="email" value="{{ old('email', $qrClient->user->email) }}" required maxlength="255" class="w-full rounded-lg border-gray-300"></div>
            <div><label for="phone" class="mb-1 block text-sm font-semibold">Nomor HP</label><input id="phone" name="phone" value="{{ old('phone', $qrClient->user->phone) }}" maxlength="30" class="w-full rounded-lg border-gray-300"></div>
            <div class="border-t pt-4"><h3 class="font-semibold">Ubah kredensial (opsional)</h3><p class="mt-1 text-xs text-gray-500">Kosongkan password dan PIN jika tidak ingin mengubahnya.</p></div>
            <div><label for="password" class="mb-1 block text-sm font-semibold">Password baru</label><input id="password" name="password" type="password" minlength="8" autocomplete="new-password" class="w-full rounded-lg border-gray-300"></div>
            <div><label for="password_confirmation" class="mb-1 block text-sm font-semibold">Ulangi password baru</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" class="w-full rounded-lg border-gray-300"></div>
            <div><label for="pin" class="mb-1 block text-sm font-semibold">PIN baru (4–8 angka)</label><input id="pin" name="pin" type="password" inputmode="numeric" pattern="[0-9]{4,8}" maxlength="8" autocomplete="off" class="w-full rounded-lg border-gray-300"></div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="clear_pin" value="1" class="rounded border-gray-300"> Hapus PIN klien</label>
            <div class="flex flex-wrap gap-3"><button type="submit" style="background-color:#15803d;color:#ffffff;" class="rounded-lg px-5 py-3 font-bold">Simpan perubahan</button><a href="{{ route('admin.qr-clients.index') }}" class="rounded-lg border px-5 py-3 font-semibold text-gray-700">Batal</a></div>
        </form>
    </div></div>
</x-app-layout>
