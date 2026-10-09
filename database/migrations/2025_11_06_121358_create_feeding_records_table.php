<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeding_records', function (Blueprint $table) {
            $table->id();

            // Kunci Asing
            $table->foreignId('shelter_id')->constrained('shelters')->onDelete('cascade')->comment('Kandang yang diberi pakan');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict')->comment('Petugas yang mencatat/melakukan pemberian pakan');

            // Atribut Waktu
            $table->date('date')->comment('Tanggal pemberian pakan');
            $table->time('time_morning')->nullable()->comment('Waktu pakan pagi diberikan.');
            $table->time('time_evening')->nullable()->comment('Waktu pakan sore diberikan.');

            // Kunci unik gabungan (agar hanya ada 1 record per kandang per hari)
            $table->unique(['shelter_id', 'date']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeding_records');
    }
};
