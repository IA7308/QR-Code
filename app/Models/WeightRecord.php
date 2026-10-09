<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheep_id',
        'user_id',
        'weighing_date',
        'weight',
        'notes',
    ];

    /**
     * Relasi: Catatan Timbangan ini milik satu Domba.
     */
    public function sheep(): BelongsTo
    {
        return $this->belongsTo(Sheep::class);
    }

    /**
     * Relasi: Catatan Timbangan ini diinput oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
