<?php

namespace App\Jobs;

use App\Models\Faq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class TranslateFaqsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    private array $ids;

    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    public function handle(): void
    {
        $query = Faq::query();

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        $query->chunk(5, function (Collection $faqs) {
            $faqs->each(fn(Faq $faq) => $faq->autoTranslate());
        });
    }
}
