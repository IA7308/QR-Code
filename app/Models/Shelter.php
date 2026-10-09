<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shelter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'capacity',
    ];

    /**
     * Relasi: Kandang ini dibuat/dikelola oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Kandang ini menampung banyak Domba.
     */
    public function sheep(): HasMany
    {
        // Foreign Key di tabel sheep adalah 'shelter_id'
        return $this->hasMany(Sheep::class);
    }

    public function feedingRecords(): HasMany
    {
        return $this->hasMany(FeedingRecord::class);
    }

    // --- Accessor Tambahan (Optional tapi Berguna) ---

    /**
     * Mendapatkan jumlah domba saat ini di kandang ini (Current Occupancy).
     * Dapat dipanggil sebagai $shelter->current_count
     */
    public function getCurrentCountAttribute(): int
    {
        return $this->sheep()->count();
    }
}
