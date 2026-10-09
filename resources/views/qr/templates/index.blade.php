<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">QR Review · Template</h2>
                <p class="mt-1 text-sm text-gray-500">Template menentukan produk dan desain. Setiap unit hasil produksi memiliki token sendiri.</p>
            </div>
            <a href="{{ route('admin.qr-templates.create') }}" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="rounded-lg px-4 py-2 text-sm font-bold shadow-sm hover:brightness-90">+ Buat Template</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="rounded-lg bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>@endif
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-5 py-4">Template</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Total unit</th><th class="px-5 py-4">Status unit</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($templates as $template)
                                <tr>
                                    <td class="px-5 py-4"><a class="font-bold text-gray-900 hover:text-green-700" href="{{ route('admin.qr-templates.show', $template) }}">{{ $template->name }}</a><p class="mt-1 text-xs text-gray-500">{{ $template->type }} · {{ $template->description }}</p></td>
                                    <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $template->status === 'ACTIVE' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">{{ $template->status }}</span></td>
                                    <td class="px-5 py-4 font-semibold">{{ number_format($template->total_units) }}</td>
                                    <td class="px-5 py-4 text-xs text-gray-600">Kosong {{ number_format($template->empty_units) }} · Aktif {{ number_format($template->active_units) }} · Nonaktif {{ number_format($template->disabled_units) }}</td>
                                    <td class="px-5 py-4 text-right"><a class="font-semibold text-green-700 hover:underline" href="{{ route('admin.qr-templates.show', $template) }}">Buka / produksi</a><form method="POST" action="{{ route('admin.qr-templates.destroy', $template) }}" class="mt-2" onsubmit="return confirm('Hapus template {{ $template->name }} beserta seluruh unit QR, destination, dan riwayat scan?')">@csrf @method('DELETE')<button type="submit" style="color:#b91c1c;" class="text-sm font-semibold underline">Hapus template</button></form></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-12 text-center text-gray-500">Belum ada template QR.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t p-4">{{ $templates->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
