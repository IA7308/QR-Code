<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeedingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelter_id',
        'user_id',
        'date',
        'time_morning',
        'time_evening',
    ];

    protected $casts = [
        'date' => 'date',
        // Kita tidak cast time_morning/evening sebagai datetime karena bisa error jika nilainya hanya "HH:MM"
    ];

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-Many ke FeedType melalui Pivot Table.
     */
    public function feedTypes(): BelongsToMany
    {
        return $this->belongsToMany(FeedType::class, 'feeding_record_feed_type')
                    ->withPivot(['quantity_morning', 'quantity_evening'])
                    ->withTimestamps();
    }
}
