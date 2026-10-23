<?php

namespace App\Jobs;

use App\Services\Translation\TranslationFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class TranslateChangesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private ?string $property_name;
    private ?string $property_new_value_name;
    private ?string $model_table_name;
    private mixed $model_table_fk_value;
    private ?string $model_table_fk_name;
    private ?string $locale;

    public function __construct($locale, $property_name, $property_new_value_name, $model_table_name, $model_table_fk_name, $model_table_fk_value)
    {
        $this->property_name = $property_name;
        $this->property_new_value_name = $property_new_value_name;
        $this->model_table_name = $model_table_name;
        $this->model_table_fk_value = $model_table_fk_value;
        $this->model_table_fk_name = $model_table_fk_name;
        $this->locale = $locale;
    }

    public function handle(): void
    {
        $new_translated_value = TranslationFactory::translate($this->property_new_value_name, $this->locale, config('app.locale'));

        DB::table($this->model_table_name)
            ->where($this->model_table_fk_name, $this->model_table_fk_value)
            ->where('locale', $this->locale)
            ->update([$this->property_name => $new_translated_value]);
    }
}
