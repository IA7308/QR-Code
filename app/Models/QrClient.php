<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrClient extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'status',
        'pin_hash',
        'password_encrypted',
        'pin_encrypted',
    ];

    protected $hidden = ['pin_hash', 'password_encrypted', 'pin_encrypted'];

    protected function casts(): array
    {
        return [
            'password_encrypted' => 'encrypted',
            'pin_encrypted' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(QrSubscription::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(QrUnit::class);
    }
}
