<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_feedback', function (Blueprint $table): void {
            $table->dropForeign(['place_id']);
            $table->unsignedBigInteger('place_id')->nullable()->change();
            $table->foreign('place_id')->references('id')->on('places')->cascadeOnDelete();
            $table->foreignId('qr_unit_id')->nullable()->constrained('qr_units')->cascadeOnDelete();
            $table->foreignId('qr_destination_id')->nullable()->constrained('qr_destinations')->cascadeOnDelete();
        });

        Schema::table('guest_feedback', function (Blueprint $table): void {
            $table->index(['qr_unit_id', 'created_at']);
        });
    }

    public function down(): void
    {
        DB::table('guest_feedback')->whereNull('place_id')->delete();

        Schema::table('guest_feedback', function (Blueprint $table): void {
            $table->dropIndex(['qr_unit_id', 'created_at']);
            $table->dropForeign(['qr_destination_id']);
            $table->dropForeign(['qr_unit_id']);
            $table->dropColumn(['qr_destination_id', 'qr_unit_id']);
            $table->dropForeign(['place_id']);
            $table->unsignedBigInteger('place_id')->nullable(false)->change();
            $table->foreign('place_id')->references('id')->on('places')->cascadeOnDelete();
        });
    }
};
