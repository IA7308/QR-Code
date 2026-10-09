<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_unit_id',
        'place_name',
        'place_address',
        'place_id',
        'activation_code',
        'maps_url',
        'review_url',
        'activated_at',
        'deactivated_at',
    ];

    protected function casts(): array
    {
        return [
            'activated_at' => 'datetime',
            'deactivated_at' => 'datetime',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(QrUnit::class, 'qr_unit_id');
    }
}
