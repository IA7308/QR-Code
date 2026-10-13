<x-app-layout>
    @php($isAdmin = auth()->user()->role === 'admin')
    @php($backUrl = $isAdmin ? route('admin.activity-logs.index') : route('client.activity-logs.index'))
    <x-slot name="header">
        <div>
            <a href="{{ $backUrl }}" class="text-sm font-semibold text-green-700">← Activity Log</a>
            <h2 class="mt-1 text-2xl font-bold text-gray-800">Detail Aktivitas</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-5 px-4 sm:px-6 lg:px-8">
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold">Informasi aktivitas</h3>
                <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-gray-500">Waktu</dt><dd class="mt-1 font-semibold">{{ $activityLog->created_at->format('d/m/Y H:i:s') }}</dd></div>
                    @if($isAdmin)<div><dt class="text-gray-500">Aktor</dt><dd class="mt-1 font-semibold">{{ $activityLog->user?->name ?? 'Akun dihapus' }} ({{ $activityLog->user?->role ?? '—' }})</dd></div>@endif
                    <div><dt class="text-gray-500">Aksi</dt><dd class="mt-1 break-all font-semibold">{{ $activityLog->action }}</dd></div>
                    <div><dt class="text-gray-500">Metode</dt><dd class="mt-1 font-mono">{{ $activityLog->method }}</dd></div>
                    <div><dt class="text-gray-500">Alamat</dt><dd class="mt-1 break-all">{{ $activityLog->url }}</dd></div>
                    <div><dt class="text-gray-500">Target</dt><dd class="mt-1 font-mono">{{ $activityLog->subject_type ? $activityLog->subject_type . ' #' . $activityLog->subject_id : '—' }}</dd></div>
                    <div><dt class="text-gray-500">Status respons</dt><dd class="mt-1 font-semibold">{{ $activityLog->response_status ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">Alamat IP</dt><dd class="mt-1 font-mono">{{ $activityLog->ip_address ?? '—' }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-gray-500">Browser / perangkat</dt><dd class="mt-1 break-all">{{ $activityLog->user_agent ?? '—' }}</dd></div>
                </dl>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold">Rincian perubahan</h3>
                @if(!empty($activityLog->details['input']))
                    <div class="mt-4 overflow-x-auto rounded-lg bg-gray-50 p-4">
                        <table class="w-full text-left text-sm">
                            <thead><tr><th class="pb-2 pr-4 text-xs uppercase text-gray-500">Kolom</th><th class="pb-2 text-xs uppercase text-gray-500">Nilai</th></tr></thead>
                            <tbody class="divide-y">
                                @foreach($activityLog->details['input'] as $key => $value)
                                    <tr><td class="py-2 pr-4 font-mono text-xs">{{ $key }}</td><td class="whitespace-pre-wrap py-2">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value === null ? '—' : (string) $value) }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-3 text-sm text-gray-500">Tidak ada kolom perubahan yang dicatat untuk aktivitas ini.</p>
                @endif
                @if(!empty($activityLog->details['route_parameters']))
                    <p class="mt-4 text-xs text-gray-500">Parameter: <span class="font-mono">{{ json_encode($activityLog->details['route_parameters'], JSON_UNESCAPED_UNICODE) }}</span></p>
                @endif
            </section>

            <a href="{{ $backUrl }}" class="inline-flex rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700">Kembali ke Activity Log</a>
        </div>
    </div>
</x-app-layout>
