<?php

namespace App\Jobs;

use App\Models\Tour;
use App\Models\TourDay;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class TranslateToursJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private array $ids;

    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    public function handle(): void
    {

        $query = Tour::query()->with('days');

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        $query->chunk(10, function (Collection $tours) {

            $tours->each(function (Tour $tour) {

                $tour->autoTranslate();
                sleep(3);

                if ($tour->seo) {
                    $tour->seo->autoTranslate();
                }

                $tour->days->each(function (TourDay $tourDay) {
                    $tourDay->autoTranslate();
                    sleep(1);
                });

            });

        });
    }
}
