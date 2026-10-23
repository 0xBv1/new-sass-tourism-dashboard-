<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $supportedCurrencies = [
            [
                'title' => 'US Dollar',
                'name' => 'USD',
                'symbol' => '$',
                'exchange_rate' => 1,
                'default' => true,
            ],
            [
                'title' => 'Euro',
                'name' => 'EUR',
                'symbol' => '€',
                'exchange_rate' => 0.92,
                'default' => false,
            ],
            [
                'title' => 'Egyptian Pound',
                'name' => 'EGP',
                'symbol' => '£',
                'exchange_rate' => 30.90,
                'default' => false,
            ],
        ];


        foreach ($supportedCurrencies as $currency) {
            Currency::firstOrCreate(['name' => $currency['name']], $currency);
        }
    }
}
