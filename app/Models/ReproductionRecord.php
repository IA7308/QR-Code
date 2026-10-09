<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ReproductionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'female_sheep_id',
        'male_sheep_id',
        'status',
        'mating_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'weaning_date',
        'offspring_count',
        'assistance_user_id',
    ];

    /**
     * PERBAIKAN UTAMA:
     * Properti $casts ini memberi tahu Laravel bahwa kolom-kolom ini
     * harus diperlakukan sebagai objek Tanggal (Carbon), bukan string.
     */
    protected $casts = [
        'mating_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'weaning_date' => 'date',
    ];

    // --- ACCESSOR (Logika Tampilan) ---

    /**
     * Mendapatkan kelas CSS untuk badge status.
     * Cara panggil: $repro->badge_style
     */
    public function getBadgeStyleAttribute()
    {
        return match ($this->status) {
            'Planned' => 'bg-gray-100 text-gray-800',
            'Mated' => 'bg-blue-100 text-blue-800',
            'Pregnant' => 'bg-purple-100 text-purple-800 border border-purple-300',
            'Delivered' => 'bg-green-100 text-green-800',
            'Failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // --- MUTATOR (Logika Otomatis) ---

    /**
     * Mutator untuk menghitung 'expected_delivery_date' saat 'mating_date' diatur.
     */
    public function setMatingDateAttribute($value)
    {
        $this->attributes['mating_date'] = $value;

        if ($value) {
            $matingDate = Carbon::parse($value);
            $this->attributes['expected_delivery_date'] = $matingDate->addDays(147);
        }
    }

    // --- RELASI ---

    public function dam(): BelongsTo
    {
        return $this->belongsTo(Sheep::class, 'female_sheep_id');
    }

    public function sire(): BelongsTo
    {
        return $this->belongsTo(Sheep::class, 'male_sheep_id');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assistance_user_id');
    }
}
