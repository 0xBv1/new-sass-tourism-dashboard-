<?php

use App\Models\Blog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation {
    /**
     * Determine if the operation is being processed asyncronously.
     */
    protected bool $async = false;

    /**
     * The queue that the job will be dispatched to.
     */
    protected string $queue = 'default';

    /**
     * A tag name, that this operation can be filtered by.
     */
    protected ?string $tag = null;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        Blog::all()->each(function (Blog $blog) {
            $blog->categories()->attach($blog->category_id);
        });

        if (Schema::hasColumn('blogs', 'category_id')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('category_id');
            });
        }
    }
};
