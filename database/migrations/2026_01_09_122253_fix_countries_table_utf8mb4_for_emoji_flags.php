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
     * Note: This migration handles both utf8mb4 (MySQL 5.5.3+) and utf8 (older MySQL)
     * For older MySQL versions, flags will be stored as Unicode escape sequences
     */
    public function up(): void
    {
        try {
            // Check MySQL version
            $version = DB::selectOne("SELECT VERSION() as version");
            $mysqlVersion = $version->version ?? '0.0.0';
            $supportsUtf8mb4 = version_compare($mysqlVersion, '5.5.3', '>=');

            if ($supportsUtf8mb4) {
                // Convert to utf8mb4 for MySQL 5.5.3+
                DB::statement("ALTER TABLE countries CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                DB::statement("ALTER TABLE countries MODIFY flag TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
                DB::statement("ALTER TABLE countries MODIFY name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
                DB::statement("ALTER TABLE countries MODIFY code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
                DB::statement("ALTER TABLE countries MODIFY phone_code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
            } else {
                // For older MySQL, ensure flag column is large enough for Unicode escape sequences
                // The flag will be stored as Unicode code points (e.g., "U+1F1E6U+1F1EB" for 🇦🇫)
                DB::statement("ALTER TABLE countries MODIFY flag TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL");
            }
        } catch (\Exception $e) {
            // If conversion fails, the FetchCountriesCommand will handle flag conversion
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert - this is a compatibility fix
    }
};
