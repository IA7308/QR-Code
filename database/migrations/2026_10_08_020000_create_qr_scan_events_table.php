<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_scan_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_unit_id')->constrained()->cascadeOnDelete();
            $table->string('event_type', 32)->index();
            $table->timestamps();
            $table->index(['event_type', 'created_at']);
            $table->index(['qr_unit_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_scan_events');
    }
};
