<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><h2 class="text-2xl font-bold text-gray-800">Analytics Klien</h2><p class="mt-1 text-sm text-gray-500">{{ $client->company_name ?: $client->user->name }} · performa QR dan ulasan tamu.</p></div>
            <a href="{{ route('client.plans') }}" style="background-color:#15803d;color:#ffffff;" class="rounded-lg px-4 py-2 font-bold">Lihat Paket</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif

            <div class="relative">
            <div class="{{ $analyticsUnlocked ? '' : 'pointer-events-none select-none' }}" style="{{ $analyticsUnlocked ? '' : 'filter: blur(6px);' }}">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Unit QR aktif</p><p class="mt-2 text-3xl font-bold">{{ number_format($activeUnitCount) }}<span class="ml-1 text-base font-medium text-gray-400">/ {{ number_format($unitCount) }}</span></p><a class="mt-2 inline-block text-sm font-semibold text-green-700" href="{{ route('client.units') }}">Lihat QR saya</a></article>
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Total scan</p><p class="mt-2 text-3xl font-bold">{{ number_format($scanCount) }}</p><p class="mt-1 text-xs text-gray-500">Sepanjang waktu</p></article>
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Klik Google Review</p><p class="mt-2 text-3xl font-bold">{{ number_format($reviewClickCount) }}</p><p class="mt-1 text-xs text-gray-500">Sepanjang waktu</p></article>
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Feedback masuk</p><p class="mt-2 text-3xl font-bold">{{ number_format($feedbackCount) }}</p><a class="mt-2 inline-block text-sm font-semibold text-green-700" href="{{ route('client.feedback') }}">Lihat feedback</a></article>
                <article class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Rata-rata rating</p><p class="mt-2 text-3xl font-bold">{{ $averageRating !== null ? number_format((float) $averageRating, 1) : '—' }}<span class="ml-1 text-amber-500">★</span></p><p class="mt-1 text-xs text-gray-500">Dari {{ number_format($feedbackCount) }} feedback</p></article>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <article class="rounded-xl bg-white p-5 shadow-sm lg:col-span-2">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div><h3 class="font-bold text-gray-900">Aktivitas 14 hari terakhir</h3><p class="mt-1 text-sm text-gray-500">Jumlah scan QR dan klik menuju ulasan per hari.</p></div>
                        <div class="flex gap-4 text-xs font-semibold"><span class="flex items-center gap-2"><i class="h-3 w-3 rounded-sm bg-green-600"></i>Scan</span><span class="flex items-center gap-2"><i class="h-3 w-3 rounded-sm bg-emerald-200"></i>Klik ulasan</span></div>
                    </div>
                    @php($trendMax = max(1, (int) ($dailyAnalytics->max('scans') ?? 0), (int) ($dailyAnalytics->max('review_clicks') ?? 0)))
                    <div class="mt-6 grid grid-cols-7 gap-x-2 gap-y-4 sm:grid-cols-14">
                        @foreach($dailyAnalytics as $day)
                            <div class="flex min-w-0 flex-col items-center" aria-label="{{ $day['date'] }}: {{ $day['scans'] }} scan, {{ $day['review_clicks'] }} klik ulasan">
                                <div class="flex h-36 items-end gap-1">
                                    <div title="{{ $day['scans'] }} scan" class="w-2 rounded-t bg-green-600 sm:w-3" style="height: {{ max(4, (int) round($day['scans'] / $trendMax * 132)) }}px"></div>
                                    <div title="{{ $day['review_clicks'] }} klik ulasan" class="w-2 rounded-t bg-emerald-200 sm:w-3" style="height: {{ max(4, (int) round($day['review_clicks'] / $trendMax * 132)) }}px"></div>
                                </div>
                                <span class="mt-2 text-[10px] text-gray-500 sm:text-xs">{{ $day['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-xs text-gray-500">Aktivitas menghitung akses dan redirect, bukan orang unik atau jumlah ulasan yang diposting.</p>
                </article>

                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <h3 class="font-bold text-gray-900">Sebaran rating</h3>
                    <p class="mt-1 text-sm text-gray-500">Total {{ number_format($feedbackCount) }} feedback</p>
                    <div class="mt-5 space-y-4">
                        @foreach(range(5, 1) as $rating)
                            @php($ratingCount = (int) ($ratingDistribution->get($rating)?->total ?? 0))
                            @php($ratingPercent = $feedbackCount > 0 ? round($ratingCount / $feedbackCount * 100) : 0)
                            <div>
                                <div class="mb-1 flex justify-between text-xs"><span class="font-semibold text-gray-700">{{ $rating }} ★</span><span class="text-gray-500">{{ number_format($ratingCount) }} ({{ $ratingPercent }}%)</span></div>
                                <div class="h-2 overflow-hidden rounded-full bg-gray-100"><div class="h-full rounded-full bg-amber-400" style="width: {{ $ratingPercent }}%"></div></div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b p-5"><h3 class="font-bold text-gray-900">Performa per QR dan tempat</h3><p class="mt-1 text-sm text-gray-500">Diurutkan dari jumlah scan terbanyak.</p></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Label QR</th><th class="px-5 py-3">Nama tempat</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Scan</th><th class="px-5 py-3">Klik ulasan</th><th class="px-5 py-3">Feedback</th><th class="px-5 py-3">Rating rata-rata</th></tr></thead>
                        <tbody class="divide-y">
                            @forelse($units as $unit)
                                @php($unitFeedback = $feedbackByUnit->get($unit->id))
                                <tr>
                                    <td class="px-5 py-3 font-mono font-semibold">{{ $unit->unit_code }}</td>
                                    <td class="px-5 py-3">{{ $unit->currentDestination?->place_name ?? 'Belum diatur' }}</td>
                                    <td class="px-5 py-3">{{ $unit->status }}</td>
                                    <td class="px-5 py-3">{{ number_format($unit->scans_count) }}</td>
                                    <td class="px-5 py-3">{{ number_format($unit->review_clicks_count) }}</td>
                                    <td class="px-5 py-3">{{ number_format((int) ($unitFeedback?->feedback_count ?? 0)) }}</td>
                                    <td class="px-5 py-3">{{ $unitFeedback?->average_rating !== null ? number_format((float) $unitFeedback->average_rating, 1) . ' ★' : '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-500">Belum ada QR yang terhubung ke akun ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
            </div>
            @unless($analyticsUnlocked)
                <div class="absolute inset-0 z-10 flex items-start justify-center rounded-xl bg-white/55 px-4 pt-24 text-center backdrop-blur-[2px]">
                    <div class="max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                        <p class="text-lg font-bold text-gray-900">Analytics tersedia di paket Starter</p>
                        <p class="mt-2 text-sm text-gray-600">Data analytics akan terbuka setelah upgrade Starter Anda diverifikasi admin.</p>
                        <a href="{{ route('client.plans') }}" style="background-color:#15803d;color:#ffffff;" class="mt-4 inline-flex rounded-lg px-4 py-2 font-bold">Upgrade ke Starter</a>
                    </div>
                </div>
            @endunless
            </div>

            <section class="grid gap-6 lg:grid-cols-3">
                <article class="rounded-xl bg-white p-5 shadow-sm lg:col-span-1">
                    <h3 class="font-bold">Subscription</h3>
                    <p class="mt-3 text-lg font-bold">{{ $subscription?->plan?->name ?? 'Belum berlangganan' }}</p>
                    <p class="mt-1 text-sm text-gray-600">Status: {{ $subscriptionDisplayStatus ?? '—' }}</p>
                    <p class="mt-1 text-sm text-gray-600">Masa aktif sampai: {{ $subscription?->ends_at?->format('d M Y') ?? '—' }}</p>
                    <p class="mt-1 text-sm text-gray-600">Kuota: {{ number_format($unitCount) }} / {{ number_format($subscription?->unit_limit ?? 0) }} unit</p>
                    @if(!$subscription)<a href="{{ route('client.plans') }}" style="background-color:#15803d;color:#ffffff;" class="mt-4 inline-flex rounded-lg px-4 py-2 font-bold">Pilih Paket</a>@endif
                </article>
                <section class="overflow-hidden rounded-xl bg-white shadow-sm lg:col-span-2">
                    <div class="border-b p-5"><h3 class="font-bold">Invoice terbaru</h3></div>
                    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-5 py-3">Invoice</th><th class="px-5 py-3">Jumlah</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Jatuh tempo</th><th></th></tr></thead><tbody class="divide-y">
                        @forelse($invoices as $invoice)<tr><td class="px-5 py-3 font-mono">{{ $invoice->invoice_number }}</td><td class="px-5 py-3">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td><td class="px-5 py-3">{{ $invoice->status }}</td><td class="px-5 py-3">{{ $invoice->due_at?->format('d M Y') ?? '—' }}</td><td class="px-5 py-3 text-right"><a class="font-semibold text-green-700" href="{{ route('client.invoices.show', $invoice) }}">Detail</a></td></tr>@empty<tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada invoice.</td></tr>@endforelse
                    </tbody></table></div>
                </section>
            </section>
        </div>
    </div>
</x-app-layout>
