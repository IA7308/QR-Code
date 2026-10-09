<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();

            // Kunci Asing
            $table->foreignId('sheep_id')->constrained('sheep')->onDelete('cascade');

            // Handler (Petugas) - Nullable karena mungkin baru dilaporkan dan belum ada yang menangani
            $table->foreignId('handler_id')->nullable()->constrained('users')->onDelete('restrict');

            // Atribut
            $table->date('record_date');

            // (PENTING) Kolom Status yang menyebabkan error Anda
            $table->enum('status', ['Reported', 'Pending Treatment', 'In Treatment', 'Completed'])->default('Reported');

            $table->string('diagnosis')->nullable();
            $table->text('treatment_details');
            $table->string('medication_used')->nullable();
            $table->string('photo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
