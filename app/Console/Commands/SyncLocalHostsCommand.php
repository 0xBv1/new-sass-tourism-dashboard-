<?php

namespace App\Console\Commands;

use App\Services\Tenancy\LocalHosts;
use Illuminate\Console\Command;

class SyncLocalHostsCommand extends Command
{
    protected $signature = 'tenancy:sync-hosts';

    protected $description = 'Write company subdomains into the local hosts file';

    public function handle(LocalHosts $hosts): int
    {
        if (! $hosts->managesLocalHosts()) {
            $this->info('Local hosts are not used for this base domain. A single *.'.config('tenancy.base_domain').' DNS record covers every company.');

            return self::SUCCESS;
        }

        $result = $hosts->sync();

        if ($result === 'written') {
            $this->info('Company subdomains were written to '.$hosts->path());
            foreach ($hosts->lines() as $line) {
                $this->line($line);
            }

            return self::SUCCESS;
        }

        $this->error('Could not update '.$hosts->path().'. Approve the Windows permission prompt, or run this command from an administrator terminal.');

        return self::FAILURE;
    }
}
