<?php

namespace App\Http\Middleware;

use App\Enums\SettingKey;
use Closure;
use Illuminate\Http\Request;

class QueueMonitorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $queue_status = setting(SettingKey::QUEUE_MONITOR_UI->value, true);
        } catch (\Throwable $throwable) {
            $queue_status = false;
        }

        abort_unless($queue_status, 404);

        return $next($request);
    }
}
