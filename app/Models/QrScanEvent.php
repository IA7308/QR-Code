<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrScanEvent extends Model
{
    public const TYPE_SCAN = 'scan';
    public const TYPE_REVIEW_CLICK = 'review_click';

    protected $fillable = ['qr_unit_id', 'event_type'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(QrUnit::class, 'qr_unit_id');
    }
}
