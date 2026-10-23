<?php

namespace App\Console\Commands;

use App\Services\Wordpress\WordpressTourImporter;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportDataCommand extends Command
{
    protected $signature = 'import:data';

    protected $description = 'Import tours';

    public function handle(): void
    {
        $fetched = 0;
        $this->output->progressStart(580);
        for ($i = 1; $i <= 6; $i++) {
            $data = Http::get('https://sunpyramidstours.com/test.php', ['page' => $i])->json();
            $fetched += count($data);
            foreach ($data as $tour) {
                $this->output->progressAdvance();
                WordpressTourImporter::seed($tour);
                sleep(0.5);
            }
        }
        File::deleteDirectory(storage_path('app/tmp/'));
        $this->output->progressFinish();

        $this->info('Fetched: ' . $fetched . ' Saved: ' . WordpressTourImporter::$saved);
    }
}
