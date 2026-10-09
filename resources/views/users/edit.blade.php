<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Pengguna: ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Role --}}
                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Peran (Role)')" />
                            <select id="role" name="role" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staf (Pegawai)</option>
                                <option value="mitra" {{ $user->role == 'mitra' ? 'selected' : '' }}>Mitra (Investor/Plasma)</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Super User)</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- No HP --}}
                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Nomor HP')" />
                            <x-text-input id="phone" class="block w-full mt-1" type="text" name="phone" :value="old('phone', $user->phone)" />
                        </div>

                        {{-- Password (Opsional) --}}
                        <div class="p-4 mb-4 border rounded-md bg-gray-50">
                            <h3 class="mb-2 text-sm font-bold text-gray-700">Ubah Password (Isi jika ingin mengganti)</h3>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-input-label for="password" :value="__('Password Baru')" />
                                    <x-text-input id="password" class="block w-full mt-1" type="password" name="password" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                                    <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('users.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
