<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catatan: Migrasi ini WAJIB dijalankan setelah create_feeding_record_feed_type_table.php
     * karena ia memodifikasi (rename/add) kolom di tabel tersebut.
     */
    public function up(): void
    {
        // 1. Modifikasi Pivot Table (Mengganti quantity menjadi quantity_morning dan menambahkan quantity_evening)
        Schema::table('feeding_record_feed_type', function (Blueprint $table) {
            // Karena 'quantity' sudah ada, kita rename lalu tambahkan yang baru
            $table->renameColumn('quantity', 'quantity_morning');
            $table->decimal('quantity_evening', 8, 2)->nullable()->after('quantity_morning');
        });

        // 2. Tambahkan kolom harga ke feed_types
        Schema::table('feed_types', function (Blueprint $table) {
            // PERBAIKAN: Mengganti unsignedDecimal menjadi decimal dan menambahkan ->unsigned()
            $table->decimal('price_per_unit', 10, 2)->nullable()->after('unit')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeding_record_feed_type', function (Blueprint $table) {
            // Jika rollback, pastikan nama kolom dikembalikan ke 'quantity'
            $table->renameColumn('quantity_morning', 'quantity');
            $table->dropColumn('quantity_evening');
        });

        Schema::table('feed_types', function (Blueprint $table) {
            $table->dropColumn('price_per_unit');
        });
    }
};
