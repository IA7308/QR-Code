<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheep_id',
        'requester_id',
        'target_partner_id',
        'status',
        'notes',
    ];

    public function sheep()
    {
        return $this->belongsTo(Sheep::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function targetPartner()
    {
        return $this->belongsTo(User::class, 'target_partner_id');
    }
}
