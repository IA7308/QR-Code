<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestFeedback extends Model
{
    protected $table = 'guest_feedback';

    protected $fillable = ['place_id', 'qr_unit_id', 'qr_destination_id', 'submission_token', 'rating', 'comment'];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(QrUnit::class, 'qr_unit_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(QrDestination::class, 'qr_destination_id');
    }
}
