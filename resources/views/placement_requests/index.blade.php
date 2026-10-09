<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Permintaan Domba Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Pesan Sukses/Info --}}
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert">
                    <span class="font-medium">Info:</span> {{ session('info') }}
                </div>
            @endif

            {{-- Validasi Error --}}
            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if($requests->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="p-4 mb-4 bg-gray-100 rounded-full">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Tidak ada permintaan baru</h3>
                            <p class="mt-1 text-gray-500">Saat ini belum ada domba yang dikirimkan Admin kepada Anda.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Tanggal Request</th>
                                        <th scope="col" class="px-6 py-3">Pengirim</th>
                                        <th scope="col" class="px-6 py-3">Info Domba</th>
                                        <th scope="col" class="px-6 py-3">Catatan</th>
                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $req)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $req->created_at->format('d M Y, H:i') }}
                                                <br>
                                                <span class="text-xs text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $req->requester->name ?? 'Admin' }}
                                                <span class="block text-xs font-normal text-gray-500">{{ $req->requester->email ?? '' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    @if($req->sheep->photo_path)
                                                        <img class="object-cover w-10 h-10 mr-3 rounded-full" src="{{ asset('storage/' . $req->sheep->photo_path) }}" alt="Foto">
                                                    @else
                                                        <div class="flex items-center justify-center w-10 h-10 mr-3 text-xs text-gray-500 bg-gray-200 rounded-full">No IMG</div>
                                                    @endif
                                                    <div>
                                                        <div class="font-bold text-indigo-600">{{ $req->sheep->tag_number }}</div>
                                                        <div class="text-xs">{{ $req->sheep->gender }} • {{ $req->sheep->type }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $req->notes ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    {{-- Tombol Terima (Memicu Modal) --}}
                                                    <button type="button"
                                                            onclick="openAcceptModal({{ $req->id }}, '{{ $req->sheep->tag_number }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        ✓ Terima
                                                    </button>

                                                    {{-- Tombol Tolak (Memicu Modal) --}}
                                                    <button type="button"
                                                            onclick="openRejectModal({{ $req->id }})"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        ✕ Tolak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TERIMA (ACCEPT) --}}
    <div id="acceptModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeAcceptModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="acceptForm" method="POST" action="">
                    @csrf
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="w-full mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="accept-modal-title">Terima Domba: <span id="accept-sheep-tag"></span></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Silakan pilih kandang untuk menempatkan domba ini.</p>

                                    <div class="mt-4">
                                        <x-input-label for="shelter_id" :value="__('Pilih Kandang')" />
                                        <select id="shelter_id" name="shelter_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            <option value="">-- Pilih Kandang --</option>
                                            @if(isset($myShelters) && $myShelters->count() > 0)
                                                @foreach($myShelters as $shelter)
                                                    <option value="{{ $shelter->id }}">
                                                        {{ $shelter->name }} ({{ $shelter->location ?? '-' }})
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>Anda belum memiliki data kandang.</option>
                                            @endif
                                        </select>
                                        @if(!isset($myShelters) || $myShelters->count() == 0)
                                            <p class="mt-1 text-xs text-red-500">Anda harus membuat data kandang terlebih dahulu di menu Kandang.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @if(!isset($myShelters) || $myShelters->count() == 0) disabled @endif>
                            Simpan & Terima
                        </button>
                        <button type="button" onclick="closeAcceptModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TOLAK (REJECT) --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeRejectModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="w-full mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Tolak Permintaan Domba</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Mohon berikan alasan penolakan agar admin mengetahui informasinya.</p>
                                    <textarea name="reason" rows="3" class="w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Kandang penuh, kondisi domba kurang sehat saat tiba..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Penolakan
                        </button>
                        <button type="button" onclick="closeRejectModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- LOGIC ACCEPT MODAL ---
        function openAcceptModal(id, tagNumber) {
            const form = document.getElementById('acceptForm');
            form.action = `/placement-requests/${id}/approve`; // Pastikan route ini sesuai web.php

            document.getElementById('accept-sheep-tag').innerText = tagNumber;
            document.getElementById('acceptModal').classList.remove('hidden');
        }

        function closeAcceptModal() {
            document.getElementById('acceptModal').classList.add('hidden');
        }

        // --- LOGIC REJECT MODAL ---
        function openRejectModal(id) {
            const form = document.getElementById('rejectForm');
            form.action = `/placement-requests/${id}/reject`; // Pastikan route ini sesuai web.php

            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
