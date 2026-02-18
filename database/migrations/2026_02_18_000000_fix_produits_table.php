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
        // 1. Fix Auto-Increment for remaining tables (Common issue on some cPanel imports)
        $tablesToFix = ['produits', 'documents', 'document_lignes', 'settings'];
        
        foreach ($tablesToFix as $table) {
            try {
                // Try to add Primary Key AND Auto Increment (in case PK is missing)
                DB::statement("ALTER TABLE `$table` MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
            } catch (\Exception $e) {
                // If PK already exists, just modify for Auto Increment
                DB::statement("ALTER TABLE `$table` MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            }
        }

        // 2. Ensure user_id exists on produits (double check from previous migration)
        Schema::table('produits', function (Blueprint $table) {
            if (!Schema::hasColumn('produits', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
        });

        // 3. Assign existing produits to user 1 if user_id is null
        try {
            $firstUserId = DB::table('users')->orderBy('id')->value('id');
            if ($firstUserId) {
                DB::table('produits')->whereNull('user_id')->update(['user_id' => $firstUserId]);
            }
        } catch (\Exception $e) {
            // Silence if users table doesn't exist yet or is empty
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration for table structure fixes
    }
};
