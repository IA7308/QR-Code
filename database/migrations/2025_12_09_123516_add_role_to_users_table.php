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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' setelah kolom 'email'
            // Default 'admin' agar user lama otomatis jadi admin (atau sesuaikan kebutuhan)
            $table->enum('role', ['admin', 'staff', 'mitra'])->default('admin')->after('email');

            // Opsional: Menambahkan kolom 'phone' atau 'address' jika diperlukan untuk Mitra/Pegawai
            $table->string('phone')->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address']);
        });
    }
};
