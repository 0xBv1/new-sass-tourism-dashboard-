<?php

namespace App\Console\Commands;

use App\Jobs\TranslateCategoriesJob;
use App\Jobs\TranslateDestinationsJob;
use App\Jobs\TranslateTourOptionsJob;
use App\Jobs\TranslateToursJob;
use Illuminate\Console\Command;

class AutoTranslateCommand extends Command
{
    protected $signature = 'auto:translate';

    protected $description = 'Run automatic translations jobs for system models';

    public function handle(): void
    {
        $delay = 0;
        TranslateDestinationsJob::dispatch()->delay(now()->addMinutes($delay));
        $delay += 10;

        TranslateCategoriesJob::dispatch()->delay(now()->addMinutes($delay));
        $delay += 10;

        TranslateTourOptionsJob::dispatch()->delay(now()->addMinutes($delay));
        $delay += 10;

        TranslateToursJob::dispatch()->delay(now()->addMinutes($delay));

        $this->info('Translation scheduled successfully!');
    }
}
