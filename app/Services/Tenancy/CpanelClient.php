<?php

namespace App\Services\Tenancy;

use RuntimeException;

class CpanelClient
{
    public function enabled(): bool
    {
        return $this->token() !== '' && $this->user() !== '';
    }

    public function ensureDatabase(string $name): void
    {
        $this->call('Mysql', 'create_database', ['name' => $name], true);

        $user = (string) config('tenancy.cpanel.db_user');

        if ($user === '') {
            throw new RuntimeException('CPANEL_DB_USER مش متسجل، فمش ممكن منح صلاحية قاعدة البيانات.');
        }

        $this->call('Mysql', 'set_privileges_on_database', [
            'user' => $user,
            'database' => $name,
            'privileges' => 'ALL PRIVILEGES',
        ]);
    }

    public function deleteDatabase(string $name): void
    {
        $this->call('Mysql', 'delete_database', ['name' => $name], true);
    }

    public function deleteSubdomain(string $host): void
    {
        $root = (string) config('tenancy.cpanel.root_domain');

        if ($host === '' || $host === $root || ($root !== '' && ! str_ends_with($host, '.'.$root))) {
            return;
        }

        if ($this->documentRoot($host) === null) {
            return;
        }

        $url = rtrim((string) config('tenancy.cpanel.endpoint'), '/')
            .'/json-api/cpanel?'
            .http_build_query([
                'cpanel_jsonapi_user' => $this->user(),
                'cpanel_jsonapi_apiversion' => '2',
                'cpanel_jsonapi_module' => 'SubDomain',
                'cpanel_jsonapi_func' => 'delsubdomain',
                'domain' => $host,
            ]);

        $json = $this->get($url);
        $result = (int) ($json['cpanelresult']['data'][0]['result'] ?? $json['cpanelresult']['event']['result'] ?? 0);

        if ($result === 1) {
            return;
        }

        $reason = (string) ($json['cpanelresult']['data'][0]['reason'] ?? '');

        if (str_contains(strtolower($reason), 'does not exist')) {
            return;
        }

        throw new RuntimeException($reason !== '' ? $reason : 'تعذر حذف السب دومين '.$host);
    }

    public function ensureSubdomain(string $host): void
    {
        $root = (string) config('tenancy.cpanel.root_domain');
        $suffix = '.'.$root;

        if ($root === '' || ! str_ends_with($host, $suffix)) {
            throw new RuntimeException('السب دومين '.$host.' مش تابع للدومين '.$root.'.');
        }

        $docroot = trim((string) config('tenancy.cpanel.docroot'), '/');
        $current = $this->documentRoot($host);

        if ($current === null) {
            $this->call('SubDomain', 'addsubdomain', [
                'domain' => substr($host, 0, -strlen($suffix)),
                'rootdomain' => $root,
                'dir' => $docroot,
            ]);

            return;
        }

        $expected = '/home/'.$this->user().'/'.$docroot;

        if ($current !== $expected) {
            $this->call('SubDomain', 'changedocroot', [
                'domain' => $host,
                'docroot' => $docroot,
            ]);
        }
    }

    protected function documentRoot(string $host): ?string
    {
        $json = $this->request('DomainInfo', 'single_domain_data', ['domain' => $host]);

        if ((int) ($json['status'] ?? 0) !== 1) {
            return null;
        }

        $root = $json['data']['documentroot'] ?? null;

        return is_string($root) && $root !== '' ? $root : null;
    }

    protected function call(string $module, string $function, array $query, bool $allowExists = false): void
    {
        $json = $this->request($module, $function, $query);
        $status = (int) ($json['status'] ?? 0);

        if ($status === 1) {
            return;
        }

        $message = $this->errorText($json);

        $normalized = strtolower($message);

        if ($allowExists && (str_contains($normalized, 'already exists') || str_contains($normalized, 'does not exist'))) {
            return;
        }

        throw new RuntimeException($message !== '' ? $message : 'cPanel رفض تنفيذ '.$module.' '.$function);
    }

    protected function request(string $module, string $function, array $query): array
    {
        $url = rtrim((string) config('tenancy.cpanel.endpoint'), '/')
            .'/execute/'.$module.'/'.$function
            .'?'.http_build_query($query);

        return $this->get($url);
    }

    protected function get(string $url): array
    {
        $header = 'Authorization: cpanel '.$this->user().':'.$this->token();
        $body = function_exists('curl_init')
            ? $this->curl($url, $header)
            : $this->fopen($url, $header);

        $json = json_decode($body, true);

        if (! is_array($json)) {
            throw new RuntimeException('رد غير مفهوم من cPanel');
        }

        return $json;
    }

    protected function curl(string $url, string $header): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [$header],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT => 60,
        ]);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new RuntimeException('تعذر الاتصال بـ cPanel: '.$error);
        }

        return $body;
    }

    protected function fopen(string $url, string $header): string
    {
        $context = stream_context_create([
            'http' => [
                'header' => $header."\r\n",
                'timeout' => 60,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);

        if ($body === false) {
            throw new RuntimeException('تعذر الاتصال بـ cPanel.');
        }

        return $body;
    }

    protected function errorText(array $json): string
    {
        $errors = $json['errors'] ?? null;

        if (is_string($errors)) {
            return $errors;
        }

        if (is_array($errors)) {
            return implode(' ', array_map('strval', $errors));
        }

        return '';
    }

    protected function user(): string
    {
        return (string) config('tenancy.cpanel.user');
    }

    protected function token(): string
    {
        return (string) config('tenancy.cpanel.token');
    }
}
