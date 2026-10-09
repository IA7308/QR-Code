<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QrUnit extends Model
{
    use HasFactory;

    public const STATUS_EMPTY = 'EMPTY';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';

    protected $fillable = ['qr_template_id', 'qr_client_id', 'unit_code', 'token', 'activation_code', 'status', 'production_batch'];

    protected $casts = ['activation_code' => 'encrypted'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(QrClient::class, 'qr_client_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(QrTemplate::class, 'qr_template_id');
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(QrDestination::class);
    }

    public function scanEvents(): HasMany
    {
        return $this->hasMany(QrScanEvent::class, 'qr_unit_id');
    }

    public function currentDestination(): HasOne
    {
        return $this->hasOne(QrDestination::class)->whereNull('deactivated_at')->latestOfMany();
    }
}
