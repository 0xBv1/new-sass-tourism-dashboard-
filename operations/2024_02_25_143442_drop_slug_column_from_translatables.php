<?php

use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asyncronously.
     */
    protected bool $async = true;

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
        $translated_models = glob(app_path('Models/Translations/*.php'));
        foreach ($translated_models as $model_file) {
            $model = '\App\Models\\' . File::name($model_file);
            if (class_exists($model)) {
                try {
                    $table = (new $model)->getTable();
                    if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'slug')) {
                        Schema::table($table, fn($t) => $t->dropColumn('slug'));
                    }
                } catch (Throwable $exception) {

                }
            }
        }
    }
};
