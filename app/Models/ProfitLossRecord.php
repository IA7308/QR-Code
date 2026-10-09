<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitLossRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'category',
        'amount',
        'description',
        'sheep_id',
        'shelter_id',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // --- RELASI ---

    public function sheep(): BelongsTo
    {
        return $this->belongsTo(Sheep::class);
    }

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
