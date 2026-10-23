<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Seo\SitemapGenerator;

class SitemapController extends Controller
{
    public function __invoke()
    {
        return response()->download(SitemapGenerator::run());
    }
}
