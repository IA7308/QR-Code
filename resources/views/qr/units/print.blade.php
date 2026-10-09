<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak {{ $units->first()->template->name }}</title>
    <style>
        * { box-sizing: border-box; }
        :root { color-scheme: light; --green: #17664d; --ink: #1c2b25; --muted: #61736b; --line: #dce7e1; }
        body { margin: 0; background: #f3f7f4; color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; }
        .toolbar { position: sticky; z-index: 5; top: 0; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 22px; background: #fff; border-bottom: 1px solid var(--line); box-shadow: 0 4px 16px #17382a0b; }
        .toolbar-title { font-size: 14px; font-weight: 800; }
        .toolbar-meta { margin-top: 3px; color: var(--muted); font-size: 12px; }
        .toolbar-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .action { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 15px; border: 1px solid var(--green); border-radius: 9px; background: var(--green); color: #fff; font-size: 13px; font-weight: 750; text-decoration: none; cursor: pointer; }
        .action.secondary { border-color: var(--line); background: #fff; color: var(--ink); }
        .sheet { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); justify-items: center; gap: 24px; max-width: 1100px; margin: 28px auto; padding: 0 20px; }
        .unit-card { width: min(100%, 350px); display: flex; flex-direction: column; align-items: center; gap: 7px; break-inside: avoid; page-break-inside: avoid; }
        .art-card { position: relative; width: 100%; aspect-ratio: 486 / 495; overflow: hidden; }
        .art-card img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: fill; }
        .qr-window { position: absolute; top: 31.1%; left: 19.75%; display: grid; width: 59.67%; aspect-ratio: 1; place-items: center; padding: 1.2%; background: #fff; }
        .qr-window svg { display: block; width: 100%; height: 100%; }
        .unit-meta { width: 100%; padding: 7px 10px; border: 1px solid var(--line); border-radius: 8px; background: #fff; text-align: center; }
        .unit-code { color: var(--ink); font: 800 12px ui-monospace, SFMono-Regular, Menlo, monospace; letter-spacing: .05em; }
        .nfc-label { margin-top: 4px; color: var(--green); font-size: 9px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
        .nfc-url { margin-top: 2px; color: var(--muted); font: 9px/1.3 ui-monospace, SFMono-Regular, Menlo, monospace; overflow-wrap: anywhere; }
        @page { size: A4 portrait; margin: 8mm; }
        @media print {
            body { background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .toolbar { display: none !important; }
            .sheet { grid-template-columns: repeat(2, 88mm); justify-content: center; justify-items: center; gap: 5mm 8mm; max-width: none; margin: 0; padding: 0; }
            .unit-card { width: 88mm; gap: 1.5mm; }
            .unit-meta { padding: 1.5mm 2mm; border-radius: 1.5mm; }
            .unit-code { font-size: 8pt; }
            .nfc-label { margin-top: 1mm; font-size: 6pt; }
            .nfc-url { margin-top: .5mm; font-size: 6pt; line-height: 1.15; }
            .qr-window { padding: 1.5%; }
        }
    </style>
</head>
<body>
    <header class="toolbar">
        <div>
            <div class="toolbar-title">{{ $units->first()->template->name }}</div>
            <div class="toolbar-meta">{{ number_format($units->count()) }} QR unik · {{ $batch ? 'Batch produksi' : 'Semua unit template' }}</div>
        </div>
        <nav class="toolbar-actions">
            @if($batch)<a class="action secondary" href="{{ route('admin.qr-units.batch.export', $batch) }}">Unduh CSV</a>@endif
            <button class="action" type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        </nav>
    </header>

    <main class="sheet">
        @foreach($units as $unit)
            @php($unitUrl = $unit->template->type === 'qr_nfc' ? route('qr.nfc.show', $unit->token) : route('qr.public.show', $unit->token))
            <article class="unit-card">
                <div class="art-card">
                    <img src="{{ asset('images/qr-review-card-frame.png') }}" alt="Frame kartu Google Review">
                    <div class="qr-window">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(280)->margin(2)->generate($unitUrl) !!}</div>
                </div>
                <div class="unit-meta">
                    <div class="unit-code">{{ $unit->unit_code }}</div>
                    @if($unit->template->type === 'qr_nfc')
                        <div class="nfc-label">URL untuk ditulis ke NFC</div>
                        <div class="nfc-url">{{ $unitUrl }}</div>
                    @endif
                </div>
            </article>
        @endforeach
    </main>
</body>
</html>
