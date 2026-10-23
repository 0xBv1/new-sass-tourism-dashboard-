<?php

namespace App\Providers;

use App\Enums\SettingKey;
use App\Enums\PaymentMethod;
use App\Payments\PaymentFactory;
use App\Payments\PaymentGateway;
use App\Channels\WhatsappChannel;
use App\Services\Recaptcha\RecaptchaService;
use App\Models\Category;
use App\Models\Tour;
use App\Observers\CategoryObserver;
use App\Observers\TourObserver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use App\Services\Translation\Google\FreeTranslator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        // Only register Livewire if application is installed
        $isInstalled = file_exists(storage_path('app/installed'));
        if ($isInstalled && class_exists(\Livewire\LivewireServiceProvider::class)) {
            $this->app->register(\Livewire\LivewireServiceProvider::class);
        }
        
        $app = $this->app;
        $this->app->make(ChannelManager::class)->extend('whatsapp', function () use ($app) {
            return $app->make(WhatsappChannel::class);
        });

        $this->app->bind(PaymentGateway::class,
            fn() => PaymentFactory::instance(request('payment_method', PaymentMethod::COD->value)));

            $this->app->bind(RecaptchaService::class, function ($app) {
                return new RecaptchaService();
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->useRequestHostForUrls();

        // Check if application is installed before accessing database
        $isInstalled = file_exists(storage_path('app/installed'));
        
        // Only access database if installed
        if ($isInstalled) {
            try {
                // Set schema default string length
                Schema::defaultStringLength(191);
                
                // Register model observers
                Category::observe(CategoryObserver::class);
                Tour::observe(TourObserver::class);
            } catch (\Exception $e) {
                // Silently fail if database is not available (during installation)
            }
        }
        
        // File macros don't require database
        File::macro('isEmptyDir', function ($path) {
            return count(glob("$path/*")) === 0;
        });
    }

    /**
     * Keep generated links on the host the visitor opened.
     * Route domains still include 127.0.0.1 for local use, and that host was winning route().
     */
    protected function useRequestHostForUrls(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $host = request()->getHost();

        if ($host === '') {
            return;
        }

        URL::forceRootUrl(request()->getSchemeAndHttpHost());
        URL::formatHostUsing(function (string $root) use ($host) {
            $rootHost = parse_url($root, PHP_URL_HOST);

            if (! in_array($rootHost, ['127.0.0.1', 'localhost'], true) || in_array($host, ['127.0.0.1', 'localhost'], true)) {
                return $root;
            }

            return request()->getScheme().'://'.$host;
        });
//        Str::macro('htmlEntityDecode', fn($value) => Str::of(html_entity_decode($value)));
//        Stringable::macro('htmlEntityDecode', fn() => new Stringable(html_entity_decode($this->value)));
    }
}
