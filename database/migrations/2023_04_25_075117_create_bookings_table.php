<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('mixed');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->text('street_address')->nullable();
            $table->enum('status', BookingStatus::all())->default(BookingStatus::PENDING->value);
            $table->string('payment_method')->default(PaymentMethod::COD->value);
            $table->string('payment_status')->default(PaymentStatus::PENDING->value);
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->decimal('sub_total_price');
            $table->decimal('total_price');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->float('currency_exchange_rate')->default(1);
            $table->text('notes')->nullable();
            $table->longText('payment_response')->nullable();
            $table->longText('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
