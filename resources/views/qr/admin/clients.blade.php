<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Klien QR</h2>
            <p class="mt-1 text-sm text-gray-500">Akun klien, subscription, dan unit yang sudah dialokasikan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr><th class="px-5 py-4">Klien</th><th class="px-5 py-4">Username / kredensial</th><th class="px-5 py-4">Email / telepon</th><th class="px-5 py-4">Status akun</th><th class="px-5 py-4">Paket terbaru</th><th class="px-5 py-4">Subscription</th><th class="px-5 py-4">Akses analytics</th><th class="px-5 py-4">QR</th><th class="px-5 py-4">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($clients as $client)
                                @php($subscription = $client->subscriptions->sortByDesc('created_at')->first())
                                @php($analyticsVerified = $client->subscriptions->contains(fn ($item) => $item->status === 'ACTIVE' && strtolower($item->plan?->name ?? '') === 'starter' && (!$item->ends_at || $item->ends_at->isFuture())))
                                <tr>
                                    <td class="px-5 py-4"><p class="font-bold">{{ $client->company_name ?: $client->user->name }}</p><p class="text-xs text-gray-500">{{ $client->user->name }}</p></td>
                                    <td class="px-5 py-4">
                                        <p class="font-mono">{{ $client->user->username ?: '—' }}</p>
                                        <details class="mt-2"><summary class="cursor-pointer text-xs font-semibold text-green-700">Tampilkan password dan PIN</summary>
                                            <dl class="mt-2 space-y-2 rounded bg-gray-50 p-3 text-xs">
                                                <div><dt class="font-semibold">Password</dt><dd class="mt-1 break-all font-mono">{{ $client->password_encrypted ?: 'Tidak tersedia untuk akun lama' }}</dd></div>
                                                <div><dt class="font-semibold">PIN</dt><dd class="mt-1 break-all font-mono">{{ $client->pin_encrypted ?: ($client->pin_hash ? 'PIN lama tersimpan sebagai hash, tidak dapat ditampilkan' : 'Tidak diatur') }}</dd></div>
                                            </dl>
                                        </details>
                                    </td>
                                    <td class="px-5 py-4">{{ $client->user->email }}<br>{{ $client->user->phone ?: '—' }}</td>
                                    <td class="px-5 py-4"><form method="POST" action="{{ route('admin.qr-clients.status', $client) }}">@csrf @method('PATCH')<select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-xs"><option value="ACTIVE" @selected($client->status === 'ACTIVE')>ACTIVE</option><option value="SUSPENDED" @selected($client->status === 'SUSPENDED')>SUSPENDED</option></select></form></td>
                                    <td class="px-5 py-4">{{ $subscription?->plan?->name ?? 'Belum memilih' }}</td>
                                    <td class="px-5 py-4">{{ $subscription?->status ?? '—' }}</td>
                                    <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $analyticsVerified ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">{{ $analyticsVerified ? 'Terverifikasi' : 'Terkunci' }}</span></td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold">{{ number_format($client->units_count) }} unit</p>
                                        @if($client->units->isNotEmpty())<ul class="mt-2 space-y-2">@foreach($client->units as $unit)<li><p class="font-mono text-xs">{{ $unit->unit_code }} <span class="font-sans text-gray-500">({{ $unit->status }})</span></p><p class="text-xs text-gray-700">{{ $unit->currentDestination?->place_name ?? 'Nama tempat belum tersedia' }}</p></li>@endforeach</ul>@else<p class="mt-1 text-xs text-gray-500">Belum ada label QR</p>@endif
                                    </td>
                                    <td class="px-5 py-4"><div class="flex flex-col items-start gap-2"><a href="{{ route('admin.qr-clients.edit', $client) }}" class="font-semibold text-green-700 underline">Edit</a><form method="POST" action="{{ route('admin.qr-clients.destroy', $client) }}" onsubmit="return confirm('Hapus akun klien ini? Subscription dan invoice ikut terhapus, sedangkan unit QR dilepas dari akun.')">@csrf @method('DELETE')<button type="submit" class="font-semibold text-red-700 underline">Hapus</button></form></div></td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-5 py-10 text-center text-gray-500">Belum ada klien QR terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t p-4">{{ $clients->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
