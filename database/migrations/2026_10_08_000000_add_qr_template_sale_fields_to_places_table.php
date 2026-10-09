<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('qr_sale_status', 32)->default('not_for_sale');
            $table->string('buyer_name')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->unsignedBigInteger('sale_price')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn(['qr_sale_status', 'buyer_name', 'buyer_phone', 'sale_price', 'paid_at']);
        });
    }
};
