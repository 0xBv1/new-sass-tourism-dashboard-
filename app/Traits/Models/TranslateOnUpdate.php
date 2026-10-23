<?php

namespace App\Traits\Models;

use App\Jobs\TranslateChangesJob;

trait TranslateOnUpdate
{
    protected static function boot(): void
    {
        parent::boot();

        static::updated(function (self $model) {
            $model->triggerTranslationJob();
        });
    }

    protected function triggerTranslationJob(): void
    {
        $table_name = $this->getTable();
        $default_locale = config('app.locale');
        $supported_locales = collect(config('translatable.locales'))->filter(fn($l) => $l !== $default_locale);

        if ($this->locale == $default_locale) {
            // translate each updated property
            $delay = 0;
            $fk_name = $this->translationFKName();
            $fk_value = $this->{$fk_name};
            foreach ($this->getChanges() as $property_name => $new_property_value) {
                //translate foreach supported locale
                foreach ($supported_locales as $locale) {
                    TranslateChangesJob::dispatch($locale, $property_name, $new_property_value, $table_name, $fk_name, $fk_value)
                        ->delay($delay)
                        ->onQueue('translations');
                    $delay += 10;
                }
            }
        }
    }

    /**
     * Translation Foreign Key Column Name
     */
    abstract function translationFKName(): string;
}
