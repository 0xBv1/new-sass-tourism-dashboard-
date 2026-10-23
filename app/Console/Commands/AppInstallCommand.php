<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AppInstallCommand extends Command
{
    protected $signature = 'app:install';

    protected $description = 'Install the central platform without wiping company databases';

    public function handle(): int
    {
        if (! File::exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        $this->warn('app:install no longer runs migrate:fresh. Company databases are created from the platform screen.');

        return $this->call('platform:install');
    }
}
