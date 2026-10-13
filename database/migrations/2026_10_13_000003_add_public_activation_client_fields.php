<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_units', function (Blueprint $table) {
            $table->boolean('public_activation_free')->default(false)->after('qr_client_id');
        });

        Schema::table('qr_clients', function (Blueprint $table) {
            $table->string('pin_hash')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('qr_clients', function (Blueprint $table) {
            $table->dropColumn('pin_hash');
        });

        Schema::table('qr_units', function (Blueprint $table) {
            $table->dropColumn('public_activation_free');
        });
    }
};
