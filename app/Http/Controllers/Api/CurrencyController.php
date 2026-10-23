<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CurrencyResource;
use App\Models\Currency;
use App\Services\Cache\AppCache;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use HasApiResponse;

    /**
     * Show list of resource
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $key = 'currencies_'. $request->getQueryString();
        if (AppCache::has($key)) {
            $currencies = AppCache::get($key, []);
        } else {
            $queryBuilder = new QueryBuilder(new Currency, $request);
            $currencies = $queryBuilder->build()->get();
            $currencies = CurrencyResource::collection($currencies);
            AppCache::put($key, $currencies->jsonSerialize(), now()->addHour());
        }

        return $this->send($currencies);
    }

    /**
     * Show single resource
     * @param mixed $id
     * @return JsonResponse
     */
    public function show(mixed $id)
    {
        $currency = Currency::findOrFail($id);
        return $this->send($currency);
    }
}
