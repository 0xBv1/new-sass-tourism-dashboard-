<?php

namespace App\Console\Commands;

use App\Services\Seo\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'generate:sitemap';

    protected $description = 'Generate Sitemap';

    public function handle(): void
    {
        SitemapGenerator::run();

        $this->info('Sitemap Generated!');
    }
}
