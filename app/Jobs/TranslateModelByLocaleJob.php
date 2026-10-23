<?php

namespace App\Jobs;

use App\Services\Translation\TranslationFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use romanzipp\QueueMonitor\Traits\IsMonitored;


class TranslateModelByLocaleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private string $locale;
    private Model $model;

    public function __construct(string $locale, Model $model)
    {
        $this->locale = $locale;

        $this->model = $model;
    }

    public function handle(): void
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $defaultLocale = config('app.locale');
        $translatedObject = [];

        $excludedFromAutoTranslate = $this->model->excludedFromAutoTranslate ?? [];

        foreach ($this->model->translatedAttributes as $attribute) {
            try {
                sleep(0.1);

                $source = $this->model->translate($defaultLocale)->$attribute ?? '';

                if (!$source || in_array($attribute, $excludedFromAutoTranslate)) {
                    continue;
                }

                $translation = TranslationFactory::translate($source, $this->locale, $defaultLocale);

                $translatedObject[$attribute] = empty($translation) ? $source : $translation;

            } catch (\Exception $exception) {
                Log::error("Can't translate This " . class_basename($this->model), [
                    'locale' => $this->locale,
                    'id' => $this->model->getKey()
                ]);
                report($exception);
            }
        }

        $this->model->update([
            $this->locale => $translatedObject
        ]);
    }
}
