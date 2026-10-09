<?php

namespace App\Services;

use App\Models\Sheep;
use App\Models\ProfitLossRecord; // Pastikan Model ini diimpor
use Carbon\Carbon;

class PriceRecommenderService
{
    // --- KONFIGURASI HARGA ---
    const HARGA_DAGING_PER_KG = 65000;
    const BONUS_BIBIT_UNGGUL_PERSEN = 0.15;
    const PENALTI_SAKIT_PERSEN = 0.15;
    const MARGIN_PROFIT_MINIMAL = 0.25;

    /**
     * Fungsi Utama: Menghitung Harga
     */
    public function calculate(Sheep $sheep): array
    {
        // 1. Ambil Data
        $beratTerakhir = $this->getBeratTerakhir($sheep);
        $totalModal    = $this->getTotalModal($sheep); // Logika baru ada di sini
        $statusSehat   = $this->cekKesehatan($sheep);

        // 2. Hitung Nilai Aset (Berdasarkan Berat Badan)
        $nilaiDaging = $beratTerakhir * self::HARGA_DAGING_PER_KG;

        // 3. Hitung Penyesuaian (Bonus/Penalti)
        $bonusUnggul = 0;
        $penaltiSakit = 0;

        if ($sheep->is_pedigree) {
            $bonusUnggul = $nilaiDaging * self::BONUS_BIBIT_UNGGUL_PERSEN;
        }

        if (!$statusSehat) {
            $penaltiSakit = $nilaiDaging * self::PENALTI_SAKIT_PERSEN;
        }

        // 4. Hitung Harga Rekomendasi
        $nilaiPasar = $nilaiDaging + $bonusUnggul - $penaltiSakit;

        // Harga Minimal Aman = Total Modal + Margin Keuntungan
        $hargaMinimalAman = $totalModal * (1 + self::MARGIN_PROFIT_MINIMAL);

        // Ambil yang tertinggi agar peternak untung maksimal
        $hargaRekomendasi = max($nilaiPasar, $hargaMinimalAman);

        return [
            'rekomendasi' => round($hargaRekomendasi, -3),
            'berat_kg'    => $beratTerakhir,
            'nilai_daging'=> $nilaiDaging,
            'total_modal' => $totalModal,
            'bonus'       => $bonusUnggul,
            'penalti'     => $penaltiSakit,
            'sehat'       => $statusSehat
        ];
    }

    // --- FUNGSI PENDUKUNG ---

    private function getBeratTerakhir(Sheep $sheep)
    {
        $lastWeight = $sheep->weightRecords()
                            ->orderByDesc('weighing_date')
                            ->orderByDesc('id')
                            ->first();

        return $lastWeight ? $lastWeight->weight : ($sheep->birth_weight ?? 0);
    }

    /**
     * Menghitung Total Modal (Biaya Langsung + Biaya Kandang Terbagi)
     */
    private function getTotalModal(Sheep $sheep)
    {
        // 1. Harga Beli Awal (Biaya Langsung)
        $modalAwal = $sheep->purchase_price ?? 0;

        // 2. Biaya Operasional Langsung (Spesifik Domba Ini)
        // Contoh: Obat khusus yang diinput dengan memilih domba ini
        $biayaLangsung = $sheep->profitLossRecords()
                               ->where('type', 'expense')
                               ->sum('amount');

        // 3. (BARU) Biaya Operasional Terbagi (Shared Cost dari Kandang)
        $biayaKandang = 0;

        // Hanya hitung jika domba punya kandang
        if ($sheep->shelter_id) {
            // Ambil semua pengeluaran untuk kandang ini yang TIDAK spesifik ke domba lain (sheep_id NULL)
            // Contoh: Beli Pakan Curah 1 Karung untuk Kandang A
            $transaksiKandang = ProfitLossRecord::where('shelter_id', $sheep->shelter_id)
                ->where('type', 'expense')
                ->whereNull('sheep_id') // Penting: Hanya ambil biaya umum kandang
                ->get();

            // Hitung jumlah penghuni kandang saat ini untuk pembagi
            $jumlahPenghuni = \App\Models\Sheep::where('shelter_id', $sheep->shelter_id)->count();

            if ($jumlahPenghuni > 0) {
                foreach ($transaksiKandang as $transaksi) {
                    // Alokasikan biaya rata: Total Biaya Transaksi / Jumlah Domba
                    // Contoh: Pakan 500rb / 10 domba = 50rb per domba
                    $biayaKandang += ($transaksi->amount / $jumlahPenghuni);
                }
            }
        }

        // Total Modal = Harga Beli + Biaya Langsung + Porsi Biaya Kandang
        return $modalAwal + $biayaLangsung + $biayaKandang;
    }

    private function cekKesehatan(Sheep $sheep)
    {
        $recordTerbaru = $sheep->healthRecords
            ->sortByDesc(function ($record) {
                return \Carbon\Carbon::parse($record->record_date)->format('Ymd') . str_pad($record->id, 10, '0', STR_PAD_LEFT);
            })
            ->first();

        if ($recordTerbaru && !in_array($recordTerbaru->status, ['Completed', 'Sembuh / Selesai'])) {
            return false; // Sakit
        }

        return true; // Sehat
    }
}
