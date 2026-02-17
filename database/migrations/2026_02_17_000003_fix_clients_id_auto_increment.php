<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. CRITICAL: Fix 'migrations' table first so this migration can be recorded!
        // (Assuming it passed or has no PK issue, keeping as is or simplifying)
        try {
            DB::statement('ALTER TABLE migrations MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
        } catch (\Exception $e) {
            // If PK exists, just add Auto Increment
            DB::statement('ALTER TABLE migrations MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // 2. Fix 'users' table (Has PK, just needs Auto Increment)
        DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        // 3. Fix 'clients' table (Missing PK AND Auto Increment)
        DB::statement('ALTER TABLE clients MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is risky as we don't know the exact previous state, 
        // but typically we wouldn't want to remove auto-increment.
    }
};
