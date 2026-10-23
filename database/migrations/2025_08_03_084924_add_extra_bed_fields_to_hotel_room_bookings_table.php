<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            $table->unsignedMediumInteger('extra_beds_count')->default(0)->after('guests_count');
            $table->decimal('extra_beds_total_price', 10, 2)->default(0)->after('extra_beds_count');
            $table->decimal('total_price', 10, 2)->default(0)->after('extra_beds_total_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'extra_beds_count',
                'extra_beds_total_price',
                'total_price'
            ]);
        });
    }
};
