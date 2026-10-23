<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CompanyInsights
{
    public function forCompany(Tenant $tenant, bool $includeUsage, bool $refresh = false): array
    {
        $done = $tenant->provision_done;
        if (is_string($done)) {
            $done = json_decode($done, true) ?: [];
        }

        return [
            'admin_name' => $tenant->admin_name,
            'admin_email' => $tenant->admin_email,
            'storage_ready' => is_dir(TenantStorage::directoryFor((string) $tenant->id)),
            'steps_done' => is_array($done) ? count($done) : 0,
            'steps_total' => count(app(CompanyProvisioner::class)->steps()),
            'usage' => $includeUsage ? $this->usage($tenant, $refresh) : null,
        ];
    }

    protected function usage(Tenant $tenant, bool $refresh): array
    {
        $key = 'platform.company.usage.'.$tenant->id;

        if ($refresh) {
            Cache::forget($key);
        }

        return Cache::remember($key, now()->addMinutes(2), function () use ($tenant) {
            try {
                $usage = $tenant->run(fn () => $this->readTenantUsage());
            } catch (Throwable $exception) {
                $usage = [
                    'error' => mb_substr($exception->getMessage(), 0, 240),
                ];
            } finally {
                if (tenancy()->initialized) {
                    tenancy()->end();
                }
            }

            return $usage;
        });
    }

    protected function readTenantUsage(): array
    {
        $count = function (string $table): int {
            if (! Schema::hasTable($table)) {
                return 0;
            }

            $query = DB::table($table);
            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull('deleted_at');
            }

            return (int) $query->count();
        };

        $bookings = $this->bookingStats();
        $settings = $this->publicSettings();

        return [
            'error' => null,
            'users' => $count('users'),
            'clients' => $count('clients'),
            'tours' => $count('tours'),
            'hotels' => $count('hotels'),
            'rooms' => $count('rooms'),
            'trips' => $count('trips'),
            'blogs' => $count('blogs'),
            'car_rentals' => $count('car_rentals'),
            'custom_trips' => $count('custom_trips'),
            'bookings' => $bookings['total'],
            'paid_bookings' => $bookings['paid'],
            'pending_bookings' => $bookings['pending'],
            'revenue' => $bookings['revenue'],
            'site_title' => $settings['site_title'],
            'phone' => $settings['phone'],
            'public_email' => $settings['email'],
            'address' => $settings['address'],
            'recent_bookings' => $bookings['recent'],
        ];
    }

    protected function bookingStats(): array
    {
        $empty = [
            'total' => 0,
            'paid' => 0,
            'pending' => 0,
            'revenue' => 0.0,
            'recent' => [],
        ];

        if (! Schema::hasTable('bookings')) {
            return $empty;
        }

        $query = DB::table('bookings');
        if (Schema::hasColumn('bookings', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        $recent = (clone $query)
            ->latest('id')
            ->limit(6)
            ->get(['id', 'first_name', 'last_name', 'email', 'status', 'payment_status', 'total_price', 'created_at'])
            ->map(fn ($row) => [
                'id' => $row->id,
                'name' => trim(($row->first_name ?? '').' '.($row->last_name ?? '')),
                'email' => $row->email,
                'status' => $row->status,
                'payment_status' => $row->payment_status,
                'total_price' => (float) $row->total_price,
                'created_at' => $row->created_at,
            ])
            ->all();

        return [
            'total' => (int) (clone $query)->count(),
            'paid' => (int) (clone $query)->where('payment_status', 'paid')->count(),
            'pending' => (int) (clone $query)->where('status', 'pending')->count(),
            'revenue' => (float) (clone $query)->where('payment_status', 'paid')->sum('total_price'),
            'recent' => $recent,
        ];
    }

    protected function publicSettings(): array
    {
        $empty = [
            'site_title' => null,
            'phone' => null,
            'email' => null,
            'address' => null,
        ];

        if (! Schema::hasTable('settings')) {
            return $empty;
        }

        $rows = DB::table('settings')
            ->whereIn('option_key', ['site_title', 'CONTACT_PHONE_NUMBER', 'email_address', 'address'])
            ->pluck('option_value', 'option_key');

        return [
            'site_title' => $this->settingText($rows['site_title'] ?? null),
            'phone' => $this->settingText($rows['CONTACT_PHONE_NUMBER'] ?? null),
            'email' => $this->settingText($rows['email_address'] ?? null),
            'address' => $this->settingText($rows['address'] ?? null),
        ];
    }

    protected function settingText(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (is_array($value)) {
            $text = $value['ar'] ?? $value['en'] ?? reset($value);
            $text = is_scalar($text) ? trim((string) $text) : '';

            return $text !== '' ? $text : null;
        }

        $text = trim((string) $value);

        return $text !== '' ? $text : null;
    }
}
