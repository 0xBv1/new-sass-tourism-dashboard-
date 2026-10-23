<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\File;

class LocalHosts
{
    public const START = '# perfect-tenancy-start';

    public const END = '# perfect-tenancy-end';

    public function sync(): string
    {
        if (! $this->managesLocalHosts()) {
            return 'skipped';
        }

        $contents = $this->render((string) @file_get_contents($this->path()));

        if ($this->write($this->path(), $contents)) {
            return 'written';
        }

        if (PHP_OS_FAMILY === 'Windows' && $this->writeElevated($contents)) {
            return 'written';
        }

        return 'failed';
    }

    public function managesLocalHosts(): bool
    {
        $base = TenantUrl::baseDomain();

        return $base === 'localhost' || str_ends_with($base, '.localhost') || str_ends_with($base, '.test');
    }

    public function ip(): string
    {
        $central = (string) config('tenancy.primary_central_domain');

        if (filter_var($central, FILTER_VALIDATE_IP)) {
            return $central;
        }

        return '127.0.0.1';
    }

    public function path(): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $root = getenv('SystemRoot') ?: 'C:\\Windows';

            return $root.'\\System32\\drivers\\etc\\hosts';
        }

        return '/etc/hosts';
    }

    public function lines(): array
    {
        $ip = $this->ip();

        return Tenant::query()
            ->with('domains')
            ->get()
            ->flatMap(fn (Tenant $tenant) => $tenant->domains->pluck('domain'))
            ->filter()
            ->unique()
            ->sort()
            ->map(fn (string $domain) => $ip.' '.$domain)
            ->values()
            ->all();
    }

    public function render(string $existing): string
    {
        $withoutBlock = preg_replace(
            '/\R?'.preg_quote(self::START, '/').'.*?'.preg_quote(self::END, '/').'\R?/s',
            PHP_EOL,
            $existing
        ) ?? $existing;

        $domains = Tenant::query()->with('domains')->get()
            ->flatMap(fn (Tenant $tenant) => $tenant->domains->pluck('domain'))
            ->filter()
            ->unique();

        $cleaned = preg_replace(
            '/^'.preg_quote($this->ip(), '/').'\s+('.implode('|', $domains->map(fn ($domain) => preg_quote($domain, '/'))->all() ?: ['$^']).')\s*$/m',
            '',
            $withoutBlock
        ) ?? $withoutBlock;

        $newline = PHP_OS_FAMILY === 'Windows' ? "\r\n" : "\n";
        $body = trim(str_replace(["\r\n", "\r"], "\n", $cleaned));
        $block = self::START."\n".implode("\n", $this->lines())."\n".self::END;

        $rendered = ($body === '' ? '' : $body."\n\n").$block."\n";

        if ($newline !== "\n") {
            $rendered = str_replace("\n", $newline, $rendered);
        }

        return $rendered;
    }

    protected function write(string $path, string $contents): bool
    {
        return @file_put_contents($path, $contents) !== false;
    }

    protected function writeElevated(string $contents): bool
    {
        $pending = storage_path('app/hosts.pending');
        $script = storage_path('app/sync-hosts.ps1');
        File::ensureDirectoryExists(dirname($pending));
        File::put($pending, $contents);

        $user = getenv('USERNAME') ?: '';
        $scriptBody = implode("\r\n", [
            '$ErrorActionPreference = "Stop"',
            "icacls '".$this->escapePowerShell($this->path())."' /grant '".$this->escapePowerShell($user).":(M)' | Out-Null",
            "Copy-Item -LiteralPath '".$this->escapePowerShell($pending)."' -Destination '".$this->escapePowerShell($this->path())."' -Force",
        ]);
        File::put($script, $scriptBody);

        $launch = '$p = Start-Process -FilePath powershell -Verb RunAs -PassThru -ArgumentList "-NoProfile","-ExecutionPolicy","Bypass","-File","'.$this->escapePowerShell($script).'"; if (-not $p.WaitForExit(45000)) { exit 2 }; exit $p.ExitCode';

        exec('powershell -NoProfile -ExecutionPolicy Bypass -EncodedCommand '.$this->encodePowerShell($launch), $output, $code);

        return $code === 0 && str_contains((string) @file_get_contents($this->path()), self::START);
    }

    protected function encodePowerShell(string $command): string
    {
        return base64_encode(mb_convert_encoding($command, 'UTF-16LE', 'UTF-8'));
    }

    protected function escapePowerShell(string $value): string
    {
        return str_replace("'", "''", $value);
    }
}
