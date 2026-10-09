<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placement_requests', function (Blueprint $table) {
            $table->id();
            // Domba yang ingin dititipkan
            $table->foreignId('sheep_id')->constrained('sheep')->onDelete('cascade');

            // Pengirim (Biasanya Admin/User ID 1)
            $table->foreignId('requester_id')->constrained('users');

            // Penerima (Mitra yang dituju)
            $table->foreignId('target_partner_id')->constrained('users');

            // Status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Catatan opsional
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placement_requests');
    }
};
