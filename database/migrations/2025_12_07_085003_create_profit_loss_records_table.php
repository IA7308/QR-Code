<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profit_loss_records', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->enum('type', ['income', 'expense']); // Pemasukan atau Pengeluaran
            $table->string('category'); // Contoh: "Jual Domba", "Beli Pakan", "Gaji"
            $table->decimal('amount', 12, 2); // Hingga ratusan milyar
            $table->string('description')->nullable();

            // Relasi Opsional (Biaya ini terkait domba/kandang mana?)
            $table->foreignId('sheep_id')->nullable()->constrained('sheep')->nullOnDelete();
            $table->foreignId('shelter_id')->nullable()->constrained('shelters')->nullOnDelete();

            // Pencatat
            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profit_loss_records');
    }
};
