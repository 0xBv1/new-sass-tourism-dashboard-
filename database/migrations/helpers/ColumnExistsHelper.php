<?php

namespace Database\Migrations\Helpers;

use Illuminate\Support\Facades\DB;

trait ColumnExistsHelper
{
    /**
     * Check if column exists using raw query to avoid MySQL version compatibility issues
     * This avoids using Schema::hasColumn() which queries generation_expression (MySQL 5.7.6+)
     * 
     * @param string $table
     * @param string $column
     * @return bool
     */
    protected function columnExists(string $table, string $column): bool
    {
        try {
            $result = DB::selectOne(
                "SELECT COUNT(*) as count FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?",
                [$table, $column]
            );
            return $result && $result->count > 0;
        } catch (\Exception $e) {
            // If query fails, assume column doesn't exist
            return false;
        }
    }
}
