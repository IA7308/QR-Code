<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sheep extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi (mass assignable).
     * Pastikan semua kolom input form ada di sini.
     */
    protected $fillable = [
        'tag_number',
        'gender',
        'date_of_birth',
        'birth_weight',
        'category',
        'type',
        'user_id',
        'shelter_id',
        'father_id',
        'mother_id',
        'purchase_price',
        'is_pedigree',
        'special_characteristics',
        'photo_path',
        'description',
        'placement_status',
        'partner_id',
        'placement_status',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'is_pedigree' => 'boolean',
    ];

    // --- RELASI SILSILAH (SELF-REFERENCING) ---

    /**
     * Relasi: Domba ini memiliki satu AYAH.
     * Menggunakan foreign key 'father_id' dan me-refer ke model Sheep itu sendiri.
     */
    public function father(): BelongsTo
    {
        return $this->belongsTo(Sheep::class, 'father_id');
    }

    /**
     * Relasi: Domba ini memiliki satu IBU.
     * Menggunakan foreign key 'mother_id' dan me-refer ke model Sheep itu sendiri.
     */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Sheep::class, 'mother_id');
    }

    /**
     * Relasi: Domba ini memiliki banyak ANAK LAKI-LAKI (sebagai ayah).
     */
    public function offspringAsFather(): HasMany
    {
        return $this->hasMany(Sheep::class, 'father_id');
    }

    /**
     * Relasi: Domba ini memiliki banyak ANAK PEREMPUAN (sebagai ibu).
     */
    public function offspringAsMother(): HasMany
    {
        return $this->hasMany(Sheep::class, 'mother_id');
    }

    // --- RELASI DENGAN MODEL LAIN (EKSTERNAL) ---

    /**
     * Relasi: Domba ini dicatat/dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Domba ini berada di satu Kandang (Shelter).
     */

    public function shelter(): BelongsTo
    {
        return $this->belongsTo(Shelter::class); // Asumsi model Kandang bernama 'Shelter'
    }

    /**
     * Relasi: Domba ini memiliki banyak Catatan Timbangan (WeightRecord).
     */

    public function weightRecords(): HasMany
    {
        return $this->hasMany(WeightRecord::class); // Asumsi model Timbang Domba bernama 'WeightRecord'
    }

    public function healthRecords(): HasMany
    {
        // Mencari semua baris di tabel 'health_records'
        // yang memiliki sheep_id sama dengan ID domba ini.
        return $this->hasMany(HealthRecord::class);
    }

    /**
     * Relasi: Domba ini pernah menjadi Induk Betina (Dam) dalam banyak siklus reproduksi.
     */
    public function asDamReproductionRecords(): HasMany
    {
        // Mencari di ReproductionRecord dengan FK 'female_sheep_id'
        return $this->hasMany(ReproductionRecord::class, 'female_sheep_id');
    }

    /**
     * Relasi: Domba ini pernah menjadi Pejantan (Sire) dalam banyak siklus reproduksi.
     */
    public function asSireReproductionRecords(): HasMany
    {
        // Mencari di ReproductionRecord dengan FK 'male_sheep_id'
        return $this->hasMany(ReproductionRecord::class, 'male_sheep_id');
    }

    public function profitLossRecords(): HasMany
    {
        return $this->hasMany(ProfitLossRecord::class);
    }

        public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

        public function latestPlacementRequest(): HasOne
    {
        return $this->hasOne(PlacementRequest::class)->latestOfMany();
    }

}
