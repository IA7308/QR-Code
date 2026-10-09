<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrInvoice extends Model
{
    protected $fillable = [
        'qr_subscription_id', 'invoice_number', 'amount', 'status', 'proof_path',
        'client_note', 'admin_note', 'due_at', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'due_at' => 'datetime', 'paid_at' => 'datetime'];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(QrSubscription::class, 'qr_subscription_id');
    }
}
