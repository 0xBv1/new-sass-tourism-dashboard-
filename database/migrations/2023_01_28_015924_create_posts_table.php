<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('pending');
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('post_translations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug', 180);
            $table->longText('content');
            $table->string('locale', 10)->index()->default(config('app.locale'));
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->unique(['post_id', 'locale', 'slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
    }
};
