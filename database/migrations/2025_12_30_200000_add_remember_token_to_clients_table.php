<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $rememberTokenExists = $this->columnExists('clients', 'remember_token');

        if (!$rememberTokenExists) {
            try {
                DB::statement("ALTER TABLE clients ADD COLUMN remember_token VARCHAR(100) NULL");
            } catch (\Exception $e) {
                // Column might already exist, ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $rememberTokenExists = $this->columnExists('clients', 'remember_token');

        if ($rememberTokenExists) {
            try {
                DB::statement("ALTER TABLE clients DROP COLUMN remember_token");
            } catch (\Exception $e) {
                // Ignore errors
            }
        }
    }

    /**
     * Check if column exists using raw query to avoid MySQL version compatibility issues
     */
    private function columnExists(string $table, string $column): bool
    {
        try {
            $result = DB::selectOne(
                "SELECT COUNT(*) as count FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?",
                [$table, $column]
            );
            return $result && $result->count > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};

