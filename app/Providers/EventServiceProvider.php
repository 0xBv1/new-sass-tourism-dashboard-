<?php

namespace App\Providers;

use App\Events\NewBookingEvent;
use App\Events\NewCarRentalEvent;
use App\Events\NewCustomTripRequestEvent;
use App\Listeners\NewBookingListener;
use App\Listeners\NewCarRentalListener;
use App\Listeners\NewCustomTripRequestListener;
use App\Models\Blog;
use App\Models\ContactRequest;
use App\Observers\BlogObserver;
use App\Observers\ContactRequestObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewBookingEvent::class => [
            NewBookingListener::class,
        ],
        NewCustomTripRequestEvent::class => [
            NewCustomTripRequestListener::class,
        ],
        NewCarRentalEvent::class => [
            NewCarRentalListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Only register observers if application is installed
        $isInstalled = file_exists(storage_path('app/installed'));
        
        if ($isInstalled) {
            try {
                ContactRequest::observe(ContactRequestObserver::class);
                Blog::observe(BlogObserver::class);
            } catch (\Exception $e) {
                // Silently fail if database is not available (during installation)
            }
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
