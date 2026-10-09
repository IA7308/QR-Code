<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shelters', function (Blueprint $table) {
            $table->id();

            // Relasi ke User
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            // Atribut Kandang
            $table->string('name')->unique(); // Nama kandang biasanya unik
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('capacity')->default(0)->comment('Kapasitas maksimal domba');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelters');
    }
};
