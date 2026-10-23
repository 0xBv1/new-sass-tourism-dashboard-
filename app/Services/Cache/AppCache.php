<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class AppCache
{
    public static function has($key): bool
    {
        try {
            return Cache::has($key);
        } catch (\Exception|\Throwable $exception) {
            return false;
        }
    }

    public static function get($key, $default = null): mixed
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception|\Throwable $exception) {
            return $default;
        }
    }

    public static function put($key, $value, $exception = null): void
    {
        if (empty($value)) {
            return;
        }

        try {
            Cache::put($key, $value, $exception);
        } catch (\Exception|\Throwable $exception) {
        }
    }

    public static function forget($key): void
    {
        try {
            Cache::forget($key);
        } catch (\Exception|\Throwable $exception) {
        }
    }

    public static function flush(): void
    {
        try {
            Cache::flush();
        } catch (\Exception|\Throwable $exception) {
        }
    }

    public static function forgetBulk($key): void
    {
        try {
            $redis = Cache::getRedis();
            $keys = $redis->keys("*$key*");
            foreach ($keys as $key) {
                $redis->del($key);
            }
        } catch (\Exception|\Throwable $exception) {
        }
    }
}
