<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tour_seasons', function (Blueprint $table) {
            $table->longText('available')->nullable()->after('enabled');
            $table->dropColumn('start_day');
            $table->dropColumn('start_month');
            $table->dropColumn('end_day');
            $table->dropColumn('end_month');
        });
    }

    public function down(): void
    {
        Schema::table('tour_seasons', function (Blueprint $table) {
            //
        });
    }
};
