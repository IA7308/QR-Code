<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrPlan extends Model
{
    protected $fillable = ['name', 'description', 'price', 'billing_cycle', 'unit_limit', 'status'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(QrSubscription::class);
    }
}
