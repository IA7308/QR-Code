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
        Schema::create('sheep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('shelter_id')->nullable()->constrained('shelters')->onDelete('restrict');

            // Atribut Identitas & Dasar
            $table->string('tag_number')->unique();
            $table->enum('gender', ['Jantan', 'Betina']);
            $table->date('date_of_birth');
            $table->decimal('birth_weight', 5, 2)->nullable();

            // Kategori
            $table->string('category')->comment('Kategori domba: Pedaging, Indukan, dll.');
            $table->string('type')->comment('Tipe/Ras domba: Garut, Texel, dll.');

            // Relasi Silsilah (Self-Referencing)
            $table->foreignId('father_id')->nullable()->constrained('sheep')->onDelete('set null');
            $table->foreignId('mother_id')->nullable()->constrained('sheep')->onDelete('set null');

            // Data Tambahan
            $table->unsignedBigInteger('purchase_price')->nullable();
            $table->boolean('is_pedigree')->default(false)->comment('Flag untuk struktur populasi/silsilah unggul');
            $table->text('special_characteristics')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sheep');
    }
};
