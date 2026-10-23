<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BlogCommentController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\RecaptchaController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\CarRentalController;
use App\Http\Controllers\Api\CustomTripController;
use App\Http\Controllers\Api\TourReviewController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\ContactRequestController;
use App\Http\Controllers\Api\Payment\PaypalController;
use App\Http\Controllers\Api\Payment\FawaterkController;
use App\Http\Controllers\Api\CustomizedTripCategoryController;
use App\Http\Controllers\Api\AmenityController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\DurationController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\TourOptionController;
// New controllers
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\SubscribeController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FaqCategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\TourPricingController;
use App\Http\Controllers\Api\HomeController;
//controllers
Route::group(['as' => 'api.', 'middleware' => ['api.localize']], function () {
    // Home page data
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    
    Route::post('/verify-recaptcha', [RecaptchaController::class, 'verify']);
    Route::group(['prefix' => 'payments', 'as' => 'payments'], function () {
        Route::group(['prefix' => 'fawaterk', 'as' => 'fawaterk.'], function () {
            Route::get('methods', [FawaterkController::class, 'methods'])->name('methods');
            Route::get('update/invoice', [FawaterkController::class, 'updateInvoice'])->name('update.invoice');
        });
        Route::group(['prefix' => 'paypal', 'as' => 'paypal.'], function () {
            Route::get('capture', [PaypalController::class, 'capture'])->name('capture');
            Route::get('cancel', [PaypalController::class, 'cancel'])->name('cancel');
        });
    });
    Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
        Route::get('list', [CartController::class, 'list'])->name('list');
        Route::post('tours/append', [CartController::class, 'appendTour'])->name('append.tour');
        Route::post('rentals/append', [CartController::class, 'appendRental'])->name('append.rental');
        Route::delete('remove/{item}', [CartController::class, 'remove'])->name('remove');
        Route::delete('clear', [CartController::class, 'clear'])->name('clear');
    });
    Route::post('/bookings', [BookingController::class, 'create'])->name('create');
    /*Auth Middleware*/
    Route::group(['middleware' => 'auth:client'], function () {
        Route::group(['prefix' => 'wishlist', 'as' => 'wishlist.'], function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::put('/{tour}/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        });
        Route::group(['prefix' => 'profile', 'as' => 'profile'], function () {
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::get('me', [ProfileController::class, 'me'])->name('me');
            Route::post('change/image', [ProfileController::class, 'changeProfileImage'])->name('change.profile.image');
            Route::post('logout', [ProfileController::class, 'logout'])->name('logout');
        });
        Route::group(['prefix' => 'coupons', 'as' => 'coupon.'], function () {
            Route::get('/{coupon:code}/validate', [CouponController::class, 'validateCoupon'])->name('validate');
        });
        Route::group(['prefix' => 'bookings', 'as' => 'bookings.'], function () {
            Route::get('/', [BookingController::class, 'index'])->name('index');
            Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        });
    });
    /*Auth Middleware*/
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('password/forget', [PasswordController::class, 'forget'])->name('client.forget.password');
        Route::post('password/reset', [PasswordController::class, 'reset'])->name('client.reset.password');
        Route::post('password/otp/verify', [PasswordController::class, 'otpVerify'])->name('client.password.otp.verify');
    });
    Route::post('custom/trips', CustomTripController::class)->name('custom.trips');
    Route::group(['prefix' => 'destinations', 'as' => 'destinations.'], function () {
        Route::get('/', [DestinationController::class, 'index'])->name('index');
        Route::get('/{slug}', [DestinationController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{slug}/tours', [CategoryController::class, 'tours'])->name('tours');
    });
    Route::group(['prefix' => 'trips', 'as' => 'trips.'], function () {
        Route::get('/', [TripController::class, 'index'])->name('index');
        Route::post('/search', [TripController::class, 'search'])->name('search');
        Route::post('/book', [TripController::class, 'book'])->name('book');
        Route::get('/{trip}/seats', [TripController::class, 'seats'])->name('seats');
        Route::get('/{trip}', [TripController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'durations', 'as' => 'durations.'], function () {
        Route::get('/', [DurationController::class, 'index'])->name('index');
        Route::get('/{slug}', [DurationController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'cities', 'as' => 'cities.'], function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
    });
    Route::group(['prefix' => 'tours', 'as' => 'tours.'], function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/stats', [TourController::class, 'stats'])->name('stats');
        Route::get('/{slug}', [TourController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'tour-options', 'as' => 'tour-options.'], function () {
        Route::get('/', [TourOptionController::class, 'index'])->name('index');
        Route::get('/tour/{tourId}', [TourOptionController::class, 'getByTour'])->name('by-tour');
        Route::get('/{id}', [TourOptionController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'currencies', 'as' => 'currencies.'], function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('index');
        Route::get('/{id}', [CurrencyController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
        Route::get('/', [CountryController::class, 'index'])->name('index');
        Route::get('/{id}', [CountryController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/{key}', [PageController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'tour-reviews', 'as' => 'tour-reviews.'], function () {
        Route::get('/', [TourReviewController::class, 'index'])->name('index');
        Route::post('/', [TourReviewController::class, 'store'])->name('store');
    });
    Route::group(['prefix' => 'contact-requests', 'as' => 'contact-requests.'], function () {
        Route::post('/', [ContactRequestController::class, 'store'])->name('store');
    });
    Route::group(['prefix' => 'blogs', 'as' => 'blogs.'], function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/{id}', [BlogController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'blog-comments', 'as' => 'blog-comments.'], function () {
        Route::get('/', [BlogCommentController::class, 'index'])->name('index');
        Route::get('/featured', [BlogCommentController::class, 'featured'])->name('featured');
        Route::get('/blog/{blogSlug}', [BlogCommentController::class, 'blogComments'])->name('blog');
        Route::post('/', [BlogCommentController::class, 'store'])->name('store');
        Route::post('/{blogComment}/reply', [BlogCommentController::class, 'reply'])->name('reply');
    });
    Route::group(['prefix' => 'locations', 'as' => 'locations.'], function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::get('/{id}', [LocationController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'car/rental', 'as' => 'car-rentals.'], function () {
        Route::post('available/destinations', [CarRentalController::class, 'searchForAvailableDestinations'])->name('available.destinations');
        Route::post('search/for/route', [CarRentalController::class, 'searchForRoute'])->name('search.for.route');
        Route::post('checkout', [CarRentalController::class, 'checkout'])->name('checkout');
    });
    Route::group(['prefix' => 'car-rentals', 'as' => 'car-rentals.'], function () {
        Route::get('/', [CarRentalController::class, 'index'])->name('index');
        Route::get('/{id}', [CarRentalController::class, 'show'])->name('show');
    });
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::group(['prefix' => 'faqs', 'as' => 'faqs.'], function () {
        Route::get('/', [FaqController::class, 'index'])->name('index');
        Route::get('/{id}', [FaqController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'blog-categories', 'as' => 'blog-categories.'], function () {
        Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
        Route::get('/{id}', [BlogCategoryController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'customized-trip-categories', 'as' => 'customized-trip-categories.'], function () {
        Route::get('/', [CustomizedTripCategoryController::class, 'index'])->name('index');
        Route::get('/{id}', [CustomizedTripCategoryController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'amenities', 'as' => 'amenities.'], function () {
        Route::get('/', [AmenityController::class, 'index'])->name('index');
        Route::get('/{id}', [AmenityController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'hotels', 'as' => 'hotels.'], function () {
        Route::get('/', [HotelController::class, 'index'])->name('index');
        Route::get('/{id}', [HotelController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'rooms', 'as' => 'rooms.'], function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/{id}', [RoomController::class, 'show'])->name('show');
    });
    
    // Appointments (Plan Trip, Nile Cruise, etc.)
    Route::group(['prefix' => 'appointments', 'as' => 'appointments.'], function () {
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::post('/nile-cruise', [AppointmentController::class, 'nileCruise'])->name('nile-cruise');
        Route::post('/plan-trip', [AppointmentController::class, 'planTrip'])->name('plan-trip');
    });
    
    // Newsletter Subscribe
    Route::group(['prefix' => 'subscribe', 'as' => 'subscribe.'], function () {
        Route::post('/', [SubscribeController::class, 'subscribe'])->name('store');
        Route::get('/unsubscribe/{token}', [SubscribeController::class, 'unsubscribe'])->name('unsubscribe');
    });
    
    // Sliders
    Route::group(['prefix' => 'sliders', 'as' => 'sliders.'], function () {
        Route::get('/', [SliderController::class, 'index'])->name('index');
        Route::get('/{key}', [SliderController::class, 'show'])->name('show');
    });
    
    // Team Members
    Route::get('/team', [EmployeeController::class, 'index'])->name('team.index');
    
    // FAQ Categories
    Route::get('/faq-categories', [FaqCategoryController::class, 'index'])->name('faq-categories.index');
    
    // Tour Comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::get('/featured', [CommentController::class, 'featured'])->name('featured');
        Route::get('/tour/{tourSlug}', [CommentController::class, 'tourComments'])->name('tour');
        Route::post('/', [CommentController::class, 'store'])->name('store');
    });
    
    // Tour Pricing
    Route::group(['prefix' => 'tours/{tour}', 'as' => 'tour-pricing.'], function () {
        Route::post('/calculate-price', [TourPricingController::class, 'calculatePrice'])->name('calculate');
        Route::get('/pricing-info', [TourPricingController::class, 'pricingInfo'])->name('info');
        Route::get('/date-prices', [TourPricingController::class, 'datePrices'])->name('date-prices');
        Route::get('/tiered-prices', [TourPricingController::class, 'tieredPrices'])->name('tiered-prices');
    });
    
    // Tour Packages
    Route::group(['prefix' => 'packages', 'as' => 'packages.'], function () {
        Route::get('/', [TourPricingController::class, 'packages'])->name('index');
        Route::get('/{slug}', [TourPricingController::class, 'packageShow'])->name('show');
    });
    
    //RoutePlace
});
