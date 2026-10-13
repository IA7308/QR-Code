<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Feedback Tamu</h2>
            <p class="mt-1 text-sm text-gray-500">Rating dan komentar yang dikirim tamu untuk tempat Anda.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Tempat</th><th class="px-5 py-4">Rating</th><th class="px-5 py-4">Komentar</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($feedback as $entry)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4">{{ $entry->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4 font-semibold">{{ $entry->place?->name ?: $entry->destination?->place_name ?: 'Tempat dihapus' }}@if($entry->unit)<span class="mt-1 block text-xs font-normal text-gray-500">QR {{ $entry->unit->unit_code }}</span>@endif</td>
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-amber-600">{{ $entry->rating }} ★</td>
                                    <td class="min-w-64 whitespace-pre-line px-5 py-4 text-gray-700">{{ $entry->comment ?: 'Tidak ada komentar' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">Belum ada feedback dari tamu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($feedback->hasPages())<div class="border-t px-5 py-4">{{ $feedback->links() }}</div>@endif
            </div>
        </div>
    </div>
</x-app-layout>
