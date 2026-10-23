<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Date-specific pricing (specific dates with custom prices)
        Schema::create('tour_date_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('adult_price', 10, 2);
            $table->decimal('child_price', 10, 2)->default(0);
            $table->decimal('infant_price', 10, 2)->default(0);
            $table->string('label')->nullable(); // e.g., "Christmas Special", "New Year"
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            
            $table->unique(['tour_id', 'date']);
        });

        // Duration-based pricing
        Schema::create('tour_duration_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->integer('min_days');
            $table->integer('max_days');
            $table->decimal('price_multiplier', 5, 2)->default(1.00); // 1.0 = 100%, 0.9 = 10% discount
            $table->decimal('fixed_discount', 10, 2)->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Early bird discounts
        Schema::create('tour_early_bird_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->integer('days_before_travel'); // Book X days before travel date
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Last minute deals
        Schema::create('tour_last_minute_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->integer('days_before_travel'); // Within X days of travel
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->integer('min_available_slots')->nullable(); // Only apply if slots >= X
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Dynamic pricing (demand-based)
        Schema::create('tour_dynamic_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->enum('trigger_type', ['occupancy', 'bookings_count', 'days_to_departure']);
            $table->integer('threshold_min'); // e.g., 70% occupancy
            $table->integer('threshold_max')->nullable();
            $table->enum('price_action', ['increase', 'decrease']);
            $table->enum('adjustment_type', ['percentage', 'fixed']);
            $table->decimal('adjustment_value', 10, 2);
            $table->integer('priority')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Tiered pricing (quantity-based with more flexibility)
        Schema::create('tour_tiered_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->string('tier_name'); // e.g., "Bronze", "Silver", "Gold"
            $table->integer('min_persons');
            $table->integer('max_persons');
            $table->decimal('adult_price', 10, 2);
            $table->decimal('child_price', 10, 2)->default(0);
            $table->decimal('infant_price', 10, 2)->default(0);
            $table->longText('benefits')->nullable(); // Additional benefits for this tier
            $table->integer('display_order')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Package pricing (bundle multiple tours/services)
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('package_price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable(); // Sum of individual prices
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->integer('max_bookings')->nullable();
            $table->integer('current_bookings')->default(0);
            $table->boolean('enabled')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Package items (tours included in package)
        Schema::create('tour_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('individual_price', 10, 2)->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // Custom pricing rules (flexible rule engine)
        Schema::create('tour_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->enum('rule_type', [
                'date_range',
                'day_of_week', 
                'month',
                'holiday',
                'nationality',
                'age_group',
                'group_size',
                'booking_channel',
                'promo_code',
                'custom'
            ]);
            $table->longText('conditions')->nullable(); // JSON conditions
            $table->enum('action_type', ['percentage', 'fixed', 'set_price']);
            $table->decimal('action_value', 10, 2);
            $table->integer('priority')->default(0); // Higher = applied first
            $table->boolean('stackable')->default(false); // Can combine with other rules
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_pricing_rules');
        Schema::dropIfExists('tour_package_items');
        Schema::dropIfExists('tour_packages');
        Schema::dropIfExists('tour_tiered_prices');
        Schema::dropIfExists('tour_dynamic_pricing');
        Schema::dropIfExists('tour_last_minute_deals');
        Schema::dropIfExists('tour_early_bird_discounts');
        Schema::dropIfExists('tour_duration_prices');
        Schema::dropIfExists('tour_date_prices');
    }
};


