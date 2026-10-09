<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'description',
        'google_maps_url',
        'google_review_url',
        'qr_sale_status',
        'buyer_name',
        'buyer_phone',
        'sale_price',
        'paid_at',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getQrTargetUrlAttribute(): ?string
    {
        return $this->google_review_url ?: $this->google_maps_url;
    }
}
