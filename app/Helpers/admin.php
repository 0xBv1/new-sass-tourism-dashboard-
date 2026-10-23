<?php


use App\Enums\SettingKey;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

if (!function_exists('admin')) {
    /**
     * returns authenticated admin user
     * @return User|Authenticatable|null
     */
    function admin(): User|Authenticatable|null
    {
        return auth('web')->user();
    }
}
if (!function_exists('str_chunk')) {
    function str_chunk(string $str, $size): array
    {
        $chunks = [];
        $offset = 0;
        for ($i = 0; $i < ceil(strlen($str) / $size) ; $i++) {
            $chunk = substr($str, $offset, $size);
            if (empty($chunk)) {
                break;
            }
            $chunks[] = $chunk;
            $offset+= $size;
        }

        return $chunks;
    }
}



if (!function_exists('logo')) {
    /**
     * returns site logo
     * @return string
     * @throws Throwable
     */
    function logo(): string
    {
        $value = setting(SettingKey::LOGO->value);
        $logo = is_array($value) ? ($value[0] ?? null) : $value;

        return $logo ?: asset('assets/admin/images/logo/logo.png');
    }
}

if (!function_exists('site_url')) {
    /**
     * returns Front End Url
     * @param string $path
     * @return string
     */
    function site_url(string $path = '/'): string
    {
        $path = str($path)->startsWith('/') ? $path : '/' . $path;
        return config('app.front_url') . $path;
    }
}

if (!function_exists('try')) {
    /**
     * try execute some statements
     * @param $callable
     * @return void
     */
    function try_exec($callable): void
    {
        try {
            $callable();
        } catch (Exception $exception) {
            report($exception);
        }
    }
}

if (!function_exists('logo')) {
    /**
     * returns site logo
     * @return string
     * @throws Throwable
     */
    function logo(): string
    {
        return setting(SettingKey::LOGO->value, true) ?? asset('assets/admin/images/logo/logo.png');
    }
}

if (!function_exists('setting')) {
    /**
     * Get setting by key
     * @param string $key
     * @param bool $parse
     * @return mixed|null
     * @throws Throwable
     */
    function setting(string $key, bool $parse = false): mixed
    {
        throw_if(!in_array($key, SettingKey::all()), new Exception('Invalid Setting Key!'));
        $options = Setting::key($key)->first()?->option_value;
        if ($parse) {
            return is_array($options) && !empty($options) ? $options[0] : '';
        }
        return $options ?? [];
    }

    
}
