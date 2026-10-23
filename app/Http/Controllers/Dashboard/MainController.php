<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Cache\AppCache;

class MainController extends Controller
{
    public function clearCache()
    {
        AppCache::flush();
        return response()->json([
            'message' => 'Cache purged successfully'
        ]);
    }
}
