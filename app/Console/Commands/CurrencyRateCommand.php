<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use App\Services\Dashboard\Currency as CurrencyExchanger;
class CurrencyRateCommand extends Command
{
    protected $signature = 'currency:rate';

    protected $description = 'Update Currencies Rates';

    public function __construct(private readonly CurrencyExchanger $currencyExchanger = new CurrencyExchanger)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $currencies = Currency::where('name', '!=','USD')
            ->where('active', true)
            ->get();
        $rates = $this->currencyExchanger->rates(targetCurrencies: $currencies->pluck('name')->toArray());
        if ($rates) {
            $this->info('Updating exchange rates');
            foreach ($currencies as $currency) {
                if (isset($rates[$currency->name])) {
                    $currency->update([
                        'exchange_rate' => $rates[$currency->name]
                    ]);
                }
            }
        }
    }
}
