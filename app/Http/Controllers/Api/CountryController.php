<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\Cache\AppCache;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;

class CountryController extends Controller
{
    use HasApiResponse;
    public function index()
    {
        $key = 'countries';
        if (AppCache::has($key)) {
            $countries = AppCache::get($key, []);
        } else {
            $countries = Country::all();
            AppCache::put($key, $countries, now()->addMonth());
        }
        return $this->send(
            data: $countries
        );
    }

    public function show($id)
    {
        $queryBuilder = new QueryBuilder(new Country, request());
        return $this->send(
            data: $queryBuilder->build()->findOrFail($id)
        );
    }
}
