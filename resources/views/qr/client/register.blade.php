<x-guest-layout>
    <form method="POST" action="{{ route('client.register.store') }}" class="space-y-4">@csrf
        <div><h1 class="text-2xl font-bold text-gray-900">Buat akun klien QR</h1><p class="mt-1 text-sm text-gray-500">Kelola subscription, invoice, dan QR yang diberikan kepada Anda.</p></div>
        <div><x-input-label for="name" value="Nama lengkap"/><x-text-input id="name" name="name" type="text" :value="old('name')" class="mt-1 block w-full" required autofocus/><x-input-error :messages="$errors->get('name')" class="mt-2"/></div>
        <div><x-input-label for="company_name" value="Nama usaha (opsional)"/><x-text-input id="company_name" name="company_name" type="text" :value="old('company_name')" class="mt-1 block w-full"/><x-input-error :messages="$errors->get('company_name')" class="mt-2"/></div>
        <div><x-input-label for="email" value="Email"/><x-text-input id="email" name="email" type="email" :value="old('email')" class="mt-1 block w-full" required/><x-input-error :messages="$errors->get('email')" class="mt-2"/></div>
        <div><x-input-label for="phone" value="Nomor telepon"/><x-text-input id="phone" name="phone" type="tel" :value="old('phone')" class="mt-1 block w-full"/><x-input-error :messages="$errors->get('phone')" class="mt-2"/></div>
        <div><x-input-label for="password" value="Password"/><x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password"/><x-input-error :messages="$errors->get('password')" class="mt-2"/></div>
        <div><x-input-label for="password_confirmation" value="Ulangi password"/><x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password"/></div>
        <button type="submit" style="background-color:#15803d;color:#ffffff;" class="w-full rounded-lg px-5 py-3 font-bold">Daftar sebagai klien</button>
        <p class="text-center text-sm text-gray-600">Sudah punya akun? <a class="font-semibold text-green-700 underline" href="{{ route('login') }}">Masuk</a></p>
    </form>
</x-guest-layout>
