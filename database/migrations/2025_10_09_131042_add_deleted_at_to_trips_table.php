<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $deletedAtExists = $this->columnExists('trips', 'deleted_at');

        if (!$deletedAtExists) {
            try {
                DB::statement("ALTER TABLE trips ADD COLUMN deleted_at TIMESTAMP NULL");
            } catch (\Exception $e) {
                // Column might already exist, ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $deletedAtExists = $this->columnExists('trips', 'deleted_at');

        if ($deletedAtExists) {
            try {
                DB::statement("ALTER TABLE trips DROP COLUMN deleted_at");
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
