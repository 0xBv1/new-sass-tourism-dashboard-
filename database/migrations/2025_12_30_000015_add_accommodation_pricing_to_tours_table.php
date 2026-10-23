<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Tour pricing type
            $table->enum('tour_for', ['pricing_group', 'package'])->default('pricing_group')->after('enabled');
            
            // Accommodation-based pricing (JSON structure for seasonal pricing)
            $table->longText('accommodation')->nullable()->after('pricing_groups');
            $table->longText('without_accommodation')->nullable()->after('accommodation');
            
            // Additional fields
            $table->integer('guests')->nullable()->after('without_accommodation');
            $table->decimal('deposit', 10, 2)->nullable()->after('guests');
            $table->integer('reward_points')->default(0)->after('deposit');
            
            // Share pricing for shared/group tours
            $table->decimal('share_adult_price', 10, 2)->nullable()->after('infant_price');
            $table->decimal('share_child_price', 10, 2)->nullable()->after('share_adult_price');
            $table->decimal('share_infant_price', 10, 2)->nullable()->after('share_child_price');
            $table->longText('share_pricing_groups')->nullable()->after('share_infant_price');
            
            // Start from price (cached calculated value)
            $table->decimal('start_from_price', 10, 2)->nullable()->after('share_pricing_groups');
            
            // Order/priority
            $table->integer('order_id')->default(0)->after('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'tour_for',
                'accommodation',
                'without_accommodation',
                'guests',
                'deposit',
                'reward_points',
                'share_adult_price',
                'share_child_price',
                'share_infant_price',
                'share_pricing_groups',
                'start_from_price',
                'order_id'
            ]);
        });
    }
};

