<?php

namespace App\Services\Dashboard;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Currency
{
    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::withHeaders([
            'apikey' => config('currency.key')
        ])->baseUrl(config('currency.url'));
    }

    public function rates($base= 'USD', $targetCurrencies=[])
    {
        if (empty($targetCurrencies)) {
            return null;
        }

        $symbols = collect($targetCurrencies)->map(fn($c) => strtoupper($c))->implode(',');

        return  $this->http->get('fixer/latest', [
            'base' => $base,
            'symbols' => $symbols
        ])->collect('rates');
    }
}
