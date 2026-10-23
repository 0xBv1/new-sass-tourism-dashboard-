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
        Schema::create('news_letter_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_letter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscribe_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, sent, failed, opened, clicked
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_letter_logs');
    }
};

