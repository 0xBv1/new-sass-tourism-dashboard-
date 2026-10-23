<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Check if column exists using raw query to avoid MySQL version compatibility issues
        // This avoids using Schema::hasColumn() which queries generation_expression (MySQL 5.7.6+)
        try {
            $result = DB::selectOne(
                "SELECT COUNT(*) as count FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'destinations' AND column_name = 'tours_count'"
            );

            if ($result && $result->count == 0) {
                Schema::table('destinations', function (Blueprint $table) {
                    $table->unsignedInteger('tours_count')->default(0)->after('slug');
                });
            }
        } catch (\Exception $e) {
            // If query fails, try to add column anyway (will fail gracefully if exists)
            try {
                Schema::table('destinations', function (Blueprint $table) {
                    $table->unsignedInteger('tours_count')->default(0)->after('slug');
                });
            } catch (\Exception $e2) {
                // Column might already exist, ignore
            }
        }
    }

    public function down(): void
    {
        // Check if column exists before dropping
        try {
            $result = DB::selectOne(
                "SELECT COUNT(*) as count FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'destinations' AND column_name = 'tours_count'"
            );

            if ($result && $result->count > 0) {
                Schema::table('destinations', function (Blueprint $table) {
                    $table->dropColumn('tours_count');
                });
            }
        } catch (\Exception $e) {
            // If query fails, try to drop column anyway
            try {
                Schema::table('destinations', function (Blueprint $table) {
                    $table->dropColumn('tours_count');
                });
            } catch (\Exception $e2) {
                // Column might not exist, ignore
            }
        }
    }
};
