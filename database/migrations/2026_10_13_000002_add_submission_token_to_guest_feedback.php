<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_feedback', function (Blueprint $table): void {
            $table->uuid('submission_token')->nullable()->unique()->after('qr_destination_id');
        });
    }

    public function down(): void
    {
        Schema::table('guest_feedback', function (Blueprint $table): void {
            $table->dropUnique(['submission_token']);
            $table->dropColumn('submission_token');
        });
    }
};
