<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL ENUM modification — must use raw SQL to add a new value
        DB::statement("ALTER TABLE inventory_logs MODIFY COLUMN reason ENUM('sale', 'restock', 'adjustment', 'order_cancelled') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE inventory_logs MODIFY COLUMN reason ENUM('sale', 'restock', 'adjustment') NOT NULL");
    }
};
