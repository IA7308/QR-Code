<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Ternak - {{ $sheep->tag_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
            text-transform: uppercase;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }

        /* Layout Profil */
        .profile-container {
            width: 100%;
            margin-bottom: 20px;
        }
        .photo-box {
            width: 150px;
            height: 150px;
            border: 1px solid #ddd;
            float: left;
            margin-right: 20px;
            overflow: hidden;
        }
        .photo-box img {
            width: 100%;
            height: auto;
        }
        .info-box {
            float: left;
            width: 60%;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
            background-color: #6b7280;
        }
        .badge-pedigree { background-color: #eab308; color: #422006; } /* Kuning */

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
            color: #1f2937;
            border-left: 4px solid #4f46e5;
            padding-left: 8px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- HEADER KOP SURAT --}}
    <div class="header">
        <h1>{{ $farm_name }}</h1>
        <p>Laporan Data Ternak & Sertifikat Silsilah</p>
        <p>Dicetak pada: {{ $print_date }}</p>
    </div>

    {{-- PROFIL UTAMA --}}
    <div class="clearfix profile-container">
        <div class="photo-box">
            @if($sheep->photo_path)
                {{-- DomPDF butuh path absolut system, bukan URL asset() --}}
                <img src="{{ public_path('storage/' . $sheep->photo_path) }}" alt="Foto Domba">
            @else
                <div style="padding: 50px 10px; text-align: center; color: #ccc;">No Photo</div>
            @endif
        </div>
        <div class="info-box">
            <table style="border: none;">
                <tr style="background: none;">
                    <td style="border: none; width: 120px; font-weight: bold;">No. Eartag</td>
                    <td style="border: none;">: {{ $sheep->tag_number }}
                        @if($sheep->is_pedigree) <span class="badge badge-pedigree">BIBIT UNGGUL</span> @endif
                    </td>
                </tr>
                <tr style="background: none;">
                    <td style="border: none; font-weight: bold;">Jenis Kelamin</td>
                    <td style="border: none;">: {{ $sheep->gender }}</td>
                </tr>
                <tr style="background: none;">
                    <td style="border: none; font-weight: bold;">Tipe / Ras</td>
                    <td style="border: none;">: {{ $sheep->type }}</td>
                </tr>
                <tr style="background: none;">
                    <td style="border: none; font-weight: bold;">Tanggal Lahir</td>
                    <td style="border: none;">: {{ $sheep->date_of_birth->format('d F Y') }} ({{ $sheep->date_of_birth->diffForHumans() }})</td>
                </tr>
                <tr style="background: none;">
                    <td style="border: none; font-weight: bold;">Kandang</td>
                    <td style="border: none;">: {{ $sheep->shelter->name ?? '-' }}</td>
                </tr>
                <tr style="background: none;">
                    <td style="border: none; font-weight: bold;">Bobot Lahir</td>
                    <td style="border: none;">: {{ $sheep->birth_weight }} kg</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- SILSILAH (PEDIGREE) --}}
    <div class="section-title">SILSILAH KELUARGA (PEDIGREE)</div>
    <table>
        <thead>
            <tr>
                <th width="20%">Posisi</th>
                <th width="30%">Eartag</th>
                <th width="50%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Ayah (Sire)</b></td>
                <td>{{ $sheep->father->tag_number ?? '-' }}</td>
                <td>{{ $sheep->father ? $sheep->father->type . ' (' . $sheep->father->category . ')' : 'Data tidak tersedia' }}</td>
            </tr>
            <tr>
                <td><b>Ibu (Dam)</b></td>
                <td>{{ $sheep->mother->tag_number ?? '-' }}</td>
                <td>{{ $sheep->mother ? $sheep->mother->type . ' (' . $sheep->mother->category . ')' : 'Data tidak tersedia' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- RIWAYAT KESEHATAN (5 Terakhir) --}}
    <div class="section-title">RIWAYAT KESEHATAN TERAKHIR</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Diagnosis</th>
                <th>Tindakan / Obat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sheep->healthRecords()->latest('record_date')->take(5)->get() as $health)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($health->record_date)->format('d/m/Y') }}</td>
                    <td>{{ $health->diagnosis }}</td>
                    <td>{{ Str::limit($health->treatment_details, 50) }}</td>
                    <td>{{ $health->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; font-style: italic;">Tidak ada riwayat penyakit. Domba sehat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- RIWAYAT PERTUMBUHAN (5 Terakhir) --}}
    <div class="section-title">RIWAYAT PERTUMBUHAN</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal Timbang</th>
                <th>Berat (KG)</th>
                <th>Pertambahan</th>
            </tr>
        </thead>
        <tbody>
            @php $prevWeight = 0; @endphp
            @forelse($sheep->weightRecords()->orderBy('weighing_date', 'asc')->take(10)->get() as $weight)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($weight->weighing_date)->format('d/m/Y') }}</td>
                    <td><b>{{ $weight->weight }} kg</b></td>
                    <td>
                        @if($prevWeight > 0)
                            @php $diff = $weight->weight - $prevWeight; @endphp
                            {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 2) }} kg
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @php $prevWeight = $weight->weight; @endphp
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada data timbangan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem JAS Farm pada {{ $print_date }}. <br>
        Scan QR Code pada domba untuk verifikasi data digital.
    </div>

</body>
</html>
