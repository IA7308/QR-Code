<x-app-layout>
    <x-slot name="header"><div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center"><div><h2 class="text-2xl font-bold text-gray-800">QR Units</h2><p class="mt-1 text-sm text-gray-500">Cari token/unit, lihat tempat aktif, reset destination, atau nonaktifkan QR.</p></div><a href="{{ route('admin.qr-templates.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold">Kelola Template</a></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
        <form method="GET" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm sm:grid-cols-4">
            <input name="search" value="{{ request('search') }}" class="rounded-lg border-gray-300" placeholder="Unit, token, nama/alamat tempat">
            <select name="template" class="rounded-lg border-gray-300"><option value="">Semua template</option>@foreach($templates as $template)<option value="{{ $template->id }}" @selected((string)request('template') === (string)$template->id)>{{ $template->name }}</option>@endforeach</select>
            <select name="status" class="rounded-lg border-gray-300"><option value="">Semua status</option>@foreach(['EMPTY','PENDING','ACTIVE','DISABLED'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
            <button style="background-color:#1f2937;color:#ffffff;border:1px solid #111827;" class="rounded-lg px-4 py-2 font-semibold shadow-sm hover:brightness-90">Filter</button>
        </form>
        <section class="overflow-hidden rounded-xl bg-white shadow-sm"><div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Unit</th><th class="px-5 py-3">Template</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Destination saat ini</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y">
            @forelse($units as $unit)<tr><td class="px-5 py-3"><div class="font-semibold">{{ $unit->currentDestination?->place_name ?: $unit->unit_code }}</div><div class="text-xs text-gray-500">{{ $unit->unit_code }}</div><div class="font-mono text-xs text-gray-500">{{ $unit->token }}</div></td><td class="px-5 py-3">{{ $unit->template->name }}</td><td class="px-5 py-3"><span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold">{{ $unit->status }}</span></td><td class="px-5 py-3">{{ $unit->currentDestination?->place_name ?: '—' }}</td><td class="px-5 py-3 text-right"><a class="font-semibold text-green-700" href="{{ route('admin.qr-units.show', $unit) }}">Detail</a></td></tr>
            @empty<tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">Tidak ada QR Unit yang cocok.</td></tr>@endforelse
        </tbody></table></div><div class="border-t p-4">{{ $units->links() }}</div></section>
    </div></div>
</x-app-layout>
