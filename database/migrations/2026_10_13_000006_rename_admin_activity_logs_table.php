<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_activity_logs') && !Schema::hasTable('activity_logs')) {
            Schema::rename('admin_activity_logs', 'activity_logs');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('activity_logs') && !Schema::hasTable('admin_activity_logs')) {
            Schema::rename('activity_logs', 'admin_activity_logs');
        }
    }
};
