<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Activity Log Akun</h2>
            <p class="mt-1 text-sm text-gray-500">Riwayat perubahan yang dilakukan dari akun klien Anda.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr><th class="px-5 py-4">Waktu</th><th class="px-5 py-4">Aksi</th><th class="px-5 py-4">Target</th><th class="px-5 py-4">Hasil</th><th></th></tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($activities as $activity)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4">{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-5 py-4"><span class="rounded bg-gray-100 px-2 py-1 font-mono text-xs">{{ $activity->method }}</span><p class="mt-2 break-all font-medium">{{ $activity->action }}</p><p class="mt-1 break-all text-xs text-gray-500">{{ $activity->url }}</p></td>
                                    <td class="px-5 py-4">@if($activity->subject_type)<span class="font-mono text-xs">{{ $activity->subject_type }} #{{ $activity->subject_id }}</span>@else<span class="text-gray-400">—</span>@endif</td>
                                    <td class="px-5 py-4"><span class="rounded-full px-2 py-1 text-xs font-bold {{ $activity->response_status >= 400 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">{{ $activity->response_status ?? '—' }}</span></td>
                                    <td class="px-5 py-4 text-right"><a href="{{ route('client.activity-logs.show', $activity) }}" class="font-semibold text-green-700 underline">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">Belum ada aktivitas akun.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t p-4">{{ $activities->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
