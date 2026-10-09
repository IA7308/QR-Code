<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type', 'status', 'created_by', 'shared_mode'];

    protected $casts = ['shared_mode' => 'boolean'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function units(): HasMany
    {
        return $this->hasMany(QrUnit::class);
    }

}
