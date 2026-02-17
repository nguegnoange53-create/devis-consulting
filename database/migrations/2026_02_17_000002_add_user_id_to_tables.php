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
        // 1. Add user_id column (nullable first to allow adding it to existing rows)
        $tables = ['clients', 'produits', 'documents'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                }
            });
        }

        // 2. Assign all existing data to the first user (User ID 1)
        // We assume User ID 1 exists because the user is using the app.
        try {
            $firstUserId = DB::table('users')->orderBy('id')->value('id');
            
            if ($firstUserId) {
                foreach ($tables as $table) {
                    DB::table($table)->update(['user_id' => $firstUserId]);
                }
            }
        } catch (\Exception $e) {
            // If users table is empty or error, we just continue. 
            // The columns are nullable so it won't break.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['clients', 'produits', 'documents'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'user_id')) {
                    // Drop foreign key first if it exists (convention: table_column_foreign)
                    $table->dropForeign([ 'user_id' ]); 
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
