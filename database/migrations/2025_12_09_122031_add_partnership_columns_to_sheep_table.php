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
        Schema::table('sheep', function (Blueprint $table) {
            // Status Penempatan:
            // 'Internal' = Di kandang sendiri (default)
            // 'Partner' = Dititipkan ke mitra
            $table->enum('placement_status', ['Internal', 'Partner'])->default('Internal')->after('type');

            // ID User Mitra (Jika status 'Partner')
            // Kolom ini nullable karena jika 'Internal', maka tidak ada mitranya.
            $table->foreignId('partner_id')->nullable()->after('shelter_id')->constrained('users')->onDelete('set null')->comment('User ID Mitra yang merawat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sheep', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['partner_id', 'placement_status']);
        });
    }
};
