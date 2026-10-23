<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployCommand extends Command
{
    protected $signature = 'deploy';

    protected $description = 'This Command runs after deployments';

    public function handle(): void
    {
        config()->set([
            'app.env' => 'local',
            'app.debug' => true
        ]);
        $this->call('migrate', ['--force' => true]);
//        $this->call('db:seed');
        if (!\File::exists(storage_path('oauth-private.key'))){
            $this->call('passport:install',['--force' => true]);
        }

        $this->call('operations:process');
        $this->call('l5-swagger:generate');
        $this->call('optimize:clear');
    }
}
