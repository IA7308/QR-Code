<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheep_id',
        'handler_id',
        'record_date',
        'diagnosis',
        'treatment_details',
        'medication_used',
        'photo_path',
        'status'
    ];

    // --- ACCESSOR (Logika Warna - Mendukung Data Lama & Baru) ---

    /**
     * Style Card (Background & Border) untuk halaman Detail
     */
    public function getCardStyleAttribute()
    {
        return match (true) {
            // MERAH (Danger): Kasus baru / belum ditangani
            $this->isStatus(['Reported', 'Pending Treatment', 'Baru Dilaporkan (Belum Ditangani)', 'Menunggu Obat/Dokter'])
                => 'bg-red-50 border-red-300',

            // KUNING/ORANYE (Warning): Sedang dirawat
            $this->isStatus(['In Treatment', 'Sedang Dalam Perawatan'])
                => 'bg-yellow-50 border-yellow-300',

            // HIJAU (Success): Sembuh
            $this->isStatus(['Completed', 'Sembuh / Selesai'])
                => 'bg-green-50 border-green-300',

            // DEFAULT (Gray)
            default => 'bg-gray-50 border-gray-200',
        };
    }

    /**
     * Style Badge (Warna Teks & Background Label) untuk Index & Detail
     */
    public function getBadgeStyleAttribute()
    {
        return match (true) {
            // MERAH
            $this->isStatus(['Reported', 'Pending Treatment', 'Baru Dilaporkan (Belum Ditangani)', 'Menunggu Obat/Dokter'])
                => 'bg-red-600 text-white',

            // KUNING
            $this->isStatus(['In Treatment', 'Sedang Dalam Perawatan'])
                => 'bg-yellow-500 text-white',

            // HIJAU
            $this->isStatus(['Completed', 'Sembuh / Selesai'])
                => 'bg-green-600 text-white',

            default => 'bg-gray-200 text-gray-800',
        };
    }

    /**
     * Helper sederhana untuk mencocokkan status dengan array pilihan
     */
    private function isStatus(array $statuses): bool
    {
        return in_array($this->status, $statuses);
    }

    // --- RELASI ---

    public function sheep(): BelongsTo
    {
        return $this->belongsTo(Sheep::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler_id');
    }

    public function symptoms(): BelongsToMany
    {
        return $this->belongsToMany(Symptom::class, 'health_record_symptom');
    }
}
