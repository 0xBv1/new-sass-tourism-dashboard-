<?php

namespace App\Console\Commands;

use App\Models\Translations\TourTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class WordpressDecodeCommand extends Command
{
    protected $signature = 'wordpress:decode';

    protected $description = 'Remove Wordpress ';

    public function handle(): void
    {
        $this->warn('In Progress...................');
        $tags = [
            "[/]", "[row]", "[/row]", "[column]", "[/column]", "[one_half]", "[/one_half]", "[checklist]", "[/checklist]"
        ];
        TourTranslation::chunk(50, function ($tourTranslations) use ($tags) {
            $tourTranslations->each(fn(TourTranslation $tourTranslation) => $tourTranslation->update([
                'overview' => Str::of($tourTranslation->overview)->replace($tags, '')->toString(),
                'highlights' => Str::of($tourTranslation->highlights)->replace($tags, '')->toString(),
            ]));
        });
        $this->info('Data has been cleaned.');
    }
}
