<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_subscriptions', function (Blueprint $table) {
            $table->string('billing_cycle', 20)->default('monthly')->after('qr_plan_id');
            $table->unsignedInteger('unit_limit')->default(1)->after('billing_cycle');
        });
    }

    public function down(): void
    {
        Schema::table('qr_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['billing_cycle', 'unit_limit']);
        });
    }
};
