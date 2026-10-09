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
        Schema::create('reproduction_records', function (Blueprint $table) {
            $table->id();

            // --- RELASI DENGAN DOMBA ---

            // Induk Betina (Dam)
            $table->foreignId('female_sheep_id')->constrained('sheep')->onDelete('cascade')->comment('ID Induk Betina');
            // Pejantan (Sire)
            $table->foreignId('male_sheep_id')->constrained('sheep')->onDelete('restrict')->comment('ID Pejantan');

            // --- DATA REPRODUKSI ---

            $table->enum('status', ['Planned', 'Mated', 'Pregnant', 'Delivered', 'Failed'])->default('Mated');
            $table->date('mating_date')->comment('Tanggal Kawin');

            // Tanggal Perkiraan Lahir (Akan dihitung otomatis)
            $table->date('expected_delivery_date')->nullable();

            $table->date('actual_delivery_date')->nullable()->comment('Tanggal Domba Melahirkan');
            $table->date('weaning_date')->nullable()->comment('Tanggal Sapih');
            $table->unsignedSmallInteger('offspring_count')->nullable()->comment('Jumlah anak yang lahir');

            // --- MANAJEMEN ---
            $table->foreignId('assistance_user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Petugas yang membantu/mencatat');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reproduction_records');
    }
};
