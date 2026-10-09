<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrasi ini harus ada di database Anda
        Schema::create('feeding_record_feed_type', function (Blueprint $table) {

            $table->foreignId('feeding_record_id')->constrained('feeding_records')->onDelete('cascade');
            $table->foreignId('feed_type_id')->constrained('feed_types')->onDelete('cascade');

            // Kolom untuk menyimpan JUMLAH yang diberikan (LAMA)
            $table->decimal('quantity', 8, 2)->comment('Jumlah pakan yang diberikan, sesuai unit di tabel feed_types.');

            $table->primary(['feeding_record_id', 'feed_type_id'], 'feeding_feed_primary');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeding_record_feed_type');
    }
};
