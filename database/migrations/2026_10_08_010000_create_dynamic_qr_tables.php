<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type', 40)->default('google_review');
            $table->string('status', 20)->default('ACTIVE');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('status');
        });

        Schema::create('qr_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_template_id')->constrained('qr_templates')->restrictOnDelete();
            $table->string('unit_code', 40);
            $table->string('token', 64)->unique();
            $table->string('status', 20)->default('EMPTY');
            $table->uuid('production_batch')->nullable()->index();
            $table->timestamps();
            $table->unique(['qr_template_id', 'unit_code']);
            $table->index(['qr_template_id', 'status']);
        });

        Schema::create('qr_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_unit_id')->constrained('qr_units')->cascadeOnDelete();
            $table->string('place_name');
            $table->string('place_address');
            $table->string('place_id', 255);
            $table->string('maps_url', 2048)->nullable();
            $table->string('review_url', 2048);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
            $table->index(['qr_unit_id', 'deactivated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_destinations');
        Schema::dropIfExists('qr_units');
        Schema::dropIfExists('qr_templates');
    }
};
