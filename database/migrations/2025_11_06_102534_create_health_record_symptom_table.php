<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_record_symptom', function (Blueprint $table) {
            // Kita tidak perlu kolom ID standar, cukup Foreign Key gabungan

            $table->foreignId('health_record_id')->constrained('health_records')->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained('symptoms')->onDelete('cascade');

            // Membuat kedua FK ini menjadi Primary Key gabungan (tidak boleh ada duplikat)
            $table->primary(['health_record_id', 'symptom_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_record_symptom');
    }
};
