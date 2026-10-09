<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weight_records', function (Blueprint $table) {
            $table->id(); // id_timbang_domba (Primary Key)

            // Foreign Key ke tabel sheep (Menggunakan ID Domba)
            $table->foreignId('sheep_id')->constrained()->onDelete('cascade');

            // Foreign Key ke tabel users (user_id)
            $table->foreignId('user_id')->constrained()->onDelete('restrict');

            // Atribut
            $table->date('weighing_date'); // tanggal_timbang
            $table->decimal('weight', 5, 2); // berat
            $table->text('notes')->nullable(); // keterangan

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weight_records');
    }
};
