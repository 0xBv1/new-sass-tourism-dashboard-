<?php

namespace App\Traits\Models;

use App\Jobs\TranslateModelByLocaleJob;
use Illuminate\Support\Facades\Schema;
use Log;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;

trait AutoTranslate
{
    private function buildTranslation($locale): array
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $defaultLocale = config('app.locale');
        $translatedObject = [];

        $excludedFromAutoTranslate = $this->excludedFromAutoTranslate ?? [];

        foreach ($this->translatedAttributes as $attribute) {
            try {

                sleep(0.1);

                $source = $this->translate($defaultLocale)->$attribute ?? '';

                if (!$source || in_array($attribute, $excludedFromAutoTranslate)) {
                    continue;
                }

                if (str($source)->length() > 5000) { //handle html content
                    $translation = str($source);
                    $matches = [];
                    $iterations = 0;
                    preg_match_all('/<([^\/>]+)[^>]*>(.*?)<\/\1>/', $source, $matches, PREG_SET_ORDER);
                    foreach ($matches as $match) {
                        $source_term = $match[2];

                        if (empty($source_term) || str($source_term)->contains('<img')) continue;

                        $translation_term = GoogleTranslate::trans(
                            string: $source_term,
                            target: $locale,
                            source: $defaultLocale
                        );

                        $translation = $translation->replace($source_term, $translation_term);

                        $iterations++;
                        if ($iterations %5 == 0) { sleep(1); }
                    }
                } else {
                    $translation = GoogleTranslate::trans(
                        string: $this->translate($defaultLocale)->$attribute ?? '',
                        target: $locale,
                        source: $defaultLocale
                    );
                }

                $translatedObject[$attribute] = $attribute == 'slug' ? Str::slug($translation) : $translation;

                if (empty($translatedObject[$attribute]) || is_null($translatedObject[$attribute]) || $translatedObject[$attribute] == '') {
                    $translatedObject[$attribute] = $source;
                }

            } catch (\Exception $exception) {
                Log::error("Can't translate This " . class_basename($this), [
                    'locale' => $locale,
                    'id' => $this->getKey()
                ]);
                report($exception);
            }
        }
        return $translatedObject;
    }

    public function autoTranslate(): bool
    {
        $locales = array_filter(
            config('translatable.locales'),
            fn($locale) => $locale != config('app.locale')
        );

        $now = now();

        foreach ($locales as $locale) {
            TranslateModelByLocaleJob::dispatch($locale, $this)->delay($now)->onQueue('translations');
            $now->addSeconds(5);
        }

        return true;
    }

    public function markAsTranslated()
    {
        try {
            return $this->forceFill([
                'translated_at' => now()
            ])->save();
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function scopeNotAutoTranslated($query)
    {
        return $query->whereNull('translated_at');
    }

    public function scopeAutoTranslated($query)
    {
        return $query->whereNotNull('translated_at');
    }
}
