<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrSubscription extends Model
{
    protected $fillable = ['qr_client_id', 'qr_plan_id', 'billing_cycle', 'unit_limit', 'status', 'starts_at', 'ends_at'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(QrClient::class, 'qr_client_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(QrPlan::class, 'qr_plan_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(QrInvoice::class);
    }
}
