<?php

namespace App\Jobs;

use App\Models\Destination;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class TranslateDestinationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private array $ids;

    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    public function handle(): void
    {
        $query = Destination::query();

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        $query->chunk(5, function (Collection $destinations) {
            $destinations->each(fn(Destination $destination) => $destination->autoTranslate());
            $destinations->each(fn(Destination $destination) => $destination->seo ? $destination->seo->autoTranslate() : null);
        });
    }
}
