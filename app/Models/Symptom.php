<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Relasi: Satu Gejala dapat terkait dengan banyak Catatan Kesehatan.
     */
    public function healthRecords(): BelongsToMany
    {
        // Laravel secara otomatis mencari pivot table 'health_record_symptom'
        return $this->belongsToMany(HealthRecord::class, 'health_record_symptom');
    }
}
