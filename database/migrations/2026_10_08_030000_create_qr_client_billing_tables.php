<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // QR clients are separate from the existing livestock-partner role.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','staff','mitra','qr_client') NOT NULL DEFAULT 'admin'");
        } else {
            Schema::table('users', fn (Blueprint $table) => $table->string('role')->default('admin')->change());
        }

        Schema::create('qr_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name')->nullable();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();
        });

        Schema::create('qr_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 14, 2);
            $table->string('billing_cycle', 20)->default('monthly');
            $table->unsignedInteger('unit_limit')->default(1);
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();
        });

        Schema::create('qr_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qr_plan_id')->constrained('qr_plans')->restrictOnDelete();
            $table->string('status', 20)->default('PENDING')->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->index(['qr_client_id', 'status']);
        });

        Schema::create('qr_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_subscription_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number', 40)->unique();
            $table->decimal('amount', 14, 2);
            $table->string('status', 20)->default('PENDING')->index();
            $table->string('proof_path')->nullable();
            $table->text('client_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::table('qr_units', function (Blueprint $table) {
            $table->foreignId('qr_client_id')->nullable()->after('qr_template_id')->constrained('qr_clients')->nullOnDelete();
            $table->index(['qr_client_id', 'status']);
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && DB::table('users')->where('role', 'qr_client')->exists()) {
            throw new RuntimeException('Cannot remove qr_client role while QR client accounts exist.');
        }

        Schema::table('qr_units', function (Blueprint $table) {
            $table->dropForeign(['qr_client_id']);
            $table->dropColumn('qr_client_id');
        });
        Schema::dropIfExists('qr_invoices');
        Schema::dropIfExists('qr_subscriptions');
        Schema::dropIfExists('qr_plans');
        Schema::dropIfExists('qr_clients');

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','staff','mitra') NOT NULL DEFAULT 'admin'");
        } else {
            Schema::table('users', fn (Blueprint $table) => $table->string('role')->default('admin')->change());
        }
    }
};
