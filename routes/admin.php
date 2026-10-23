<?php
use App\Http\Controllers\Dashboard\AutoTranslationController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\Dashboard\BookingController;
use App\Http\Controllers\Dashboard\CarRentalController;
use App\Http\Controllers\Dashboard\CarRouteController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\ContactRequestController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\CurrencyController;
use App\Http\Controllers\Dashboard\CustomTripController;
use App\Http\Controllers\Dashboard\DestinationController;
use App\Http\Controllers\Dashboard\LocationController;
use App\Http\Controllers\Dashboard\MainController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\SitemapController;
use App\Http\Controllers\Dashboard\TourController;
use App\Http\Controllers\Dashboard\TourOptionController;
use App\Http\Controllers\Dashboard\TourReviewController;
use App\Http\Controllers\Dashboard\TripController;
use App\Http\Controllers\Dashboard\TripBookingController;
use App\Http\Controllers\Dashboard\HotelRoomBookingController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\FaqController;
use App\Http\Controllers\Dashboard\BlogCategoryController;
use App\Http\Controllers\Dashboard\CustomizedTripCategoryController;
use App\Http\Controllers\Dashboard\RedirectRuleController;
use App\Http\Controllers\Dashboard\AmenityController;
use App\Http\Controllers\Dashboard\HotelController;
use App\Http\Controllers\Dashboard\RoomController;
use App\Http\Controllers\Dashboard\AccommodationCategoryController;
use App\Http\Controllers\Dashboard\GroupSizeController;
// New controllers
use App\Http\Controllers\Dashboard\AppointmentController;
use App\Http\Controllers\Dashboard\SubscribeController;
use App\Http\Controllers\Dashboard\NewsLetterController;
use App\Http\Controllers\Dashboard\DiscountController;
use App\Http\Controllers\Dashboard\RaiseController;
use App\Http\Controllers\Dashboard\SliderController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\FaqCategoryController;
use App\Http\Controllers\Dashboard\CommentController;
use App\Http\Controllers\Dashboard\BlogCommentController;
use App\Http\Controllers\Dashboard\TourPricingController;
use App\Http\Controllers\Dashboard\TourPackageController;
use App\Http\Controllers\Dashboard\ApiDocumentationController;
use App\Http\Controllers\Dashboard\MediaCompressionController;
//controllers
Route::group(['prefix' => 'dashboard',
    'middleware' => ['auth:web', 'permitted'],
    'as' => 'dashboard.'], function () {
    Route::get('toggle-theme', [ProfileController::class, 'toggleTheme'])->name('toggle-theme');
    Route::post('cache/clear', [MainController::class, 'clearCache'])->name('cache.clear');
    Route::post('translate'  , [AutoTranslationController::class, 'translate'])->name('model.auto.translate');
    Route::get('sitemap/generate'  , SitemapController::class)->name('sitemap.generate');
    Route::get('api-documentation', [ApiDocumentationController::class, 'index'])->name('api-documentation.index');
    Route::get('media-compression', [MediaCompressionController::class, 'index'])->name('media-compression.index');
    Route::get('media-compression/test', [MediaCompressionController::class, 'test'])->name('media-compression.test');
    Route::post('media-compression/compress', [MediaCompressionController::class, 'compress'])->name('media-compression.compress');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('roles', RoleController::class)->except('show');
    Route::resource('clients', ClientController::class)->except('show');
    Route::resource('destinations', DestinationController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('coupons', CouponController::class)->except('show');
    Route::resource('tours', TourController::class)->except('show');
    Route::resource('trips', TripController::class);
    Route::get('trips/{trip}/bookings', [TripController::class, 'tripBookings'])->name('trips.trip-bookings');
    Route::patch('trips/{trip}/toggle-status', [TripController::class, 'toggleStatus'])->name('trips.toggle-status');
    Route::get('trips/{trip}/details', [TripController::class, 'getTripDetails'])->name('trips.details');
    
    Route::resource('trip-bookings', TripBookingController::class)->except('show');
    Route::patch('trip-bookings/{tripBooking}/toggle-status', [TripBookingController::class, 'toggleStatus'])->name('trip-bookings.toggle-status');
    Route::patch('trip-bookings/{tripBooking}/cancel', [TripBookingController::class, 'cancel'])->name('trip-bookings.cancel');
    Route::get('trip-bookings/{tripBooking}/export', [TripBookingController::class, 'export'])->name('trip-bookings.export');
    Route::get('trip-bookings/{tripBooking}/invoice', [TripBookingController::class, 'invoice'])->name('trip-bookings.invoice');
    
    Route::resource('tour-options', TourOptionController::class)->except('show');
    Route::resource('currencies', CurrencyController::class)->except('show');
    Route::get('currencies/rates/update', [CurrencyController::class, 'updateRates'])->name('currencies.rates.update');
    Route::resource('bookings', BookingController::class)->except(['create', 'store', 'edit', 'destroy']);
    Route::group(['prefix' => 'custom-trips', 'as' => 'custom-trips.'], function () {
        Route::get('/', [CustomTripController::class, 'index'])->name('index');
        Route::get('/{customTrip}', [CustomTripController::class, 'show'])->name('show');
        Route::put('/{customTrip}', [CustomTripController::class, 'assign'])->name('assign');
    });
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('show', [SettingController::class, 'show'])->name('show');
        Route::put('update', [SettingController::class, 'update'])->name('update');
    });
    Route::resource('pages', PageController::class)->except('show');
    Route::resource('tour-reviews', TourReviewController::class)->only('index');
    Route::resource('contact-requests', ContactRequestController::class)->except('show');
    Route::post('contact-requests/mark-as-spam', [ContactRequestController::class, 'markAsSpam'])
        ->name('contact-requests.mark-as-spam');
    Route::resource('blogs', BlogController::class)->except('show');
    Route::resource('locations', LocationController::class)->except('show');
    Route::resource('car-routes', CarRouteController::class)->except('show');
    Route::post('car-routes/import', [CarRouteController::class, 'import'])->name('car-routes.import');
    Route::get('car-routes/template', [CarRouteController::class, 'template'])->name('car-routes.template');
    Route::group(['prefix' => 'car-rentals', 'as' => 'car-rentals.'], function () {
        Route::get('/', [CarRentalController::class, 'index'])->name('index');
        Route::get('/{carRental}', [CarRentalController::class, 'show'])->name('show');
    });
    Route::resource('faqs', FaqController::class)->except('show');
    Route::resource('blog-categories', BlogCategoryController::class)->except('show');
    Route::resource('customized-trip-categories', CustomizedTripCategoryController::class)->except('show');
    Route::resource('redirect-rules', RedirectRuleController::class)->except('show');
    Route::get('redirect-rules/export', [RedirectRuleController::class, 'export'])->name('redirect-rules.export');
    Route::post('redirect-rules/import', [RedirectRuleController::class, 'import'])->name('redirect-rules.import');
    Route::resource('amenities', AmenityController::class)->except('show');
    Route::resource('hotels', HotelController::class)->except('show');
    Route::get('hotels/{hotel}/rooms', [HotelController::class, 'rooms'])->name('hotels.rooms');
    Route::resource('rooms', RoomController::class)->except('show');
    Route::resource('accommodation-categories', AccommodationCategoryController::class)->except('show');
    Route::resource('group-sizes', GroupSizeController::class)->except('show');
    
    Route::resource('hotel_room_bookings', HotelRoomBookingController::class);
    
    // Appointments
    Route::group(['prefix' => 'appointments', 'as' => 'appointments.'], function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
        Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
    });
    
    // Subscribers
    Route::resource('subscribes', SubscribeController::class)->except('show');
    
    // Newsletters
    Route::resource('newsletters', NewsLetterController::class)->except('show');
    Route::post('newsletters/{newsletter}/send', [NewsLetterController::class, 'send'])->name('newsletters.send');
    Route::get('newsletters/{newsletter}/preview', [NewsLetterController::class, 'preview'])->name('newsletters.preview');
    
    // Discounts (price reductions)
    Route::resource('discounts', DiscountController::class)->except('show');
    
    // Raises (price increases)
    Route::resource('raises', RaiseController::class)->except('show');
    
    // Sliders
    Route::resource('sliders', SliderController::class)->except('show');
    Route::post('sliders/{slider}/slides', [SliderController::class, 'addSlide'])->name('sliders.add-slide');
    Route::delete('sliders/{slider}/slides/{slide}', [SliderController::class, 'removeSlide'])->name('sliders.remove-slide');
    
    // Team Members (Employees)
    Route::resource('employees', EmployeeController::class)->except('show');
    
    // FAQ Categories
    Route::resource('faq-categories', FaqCategoryController::class)->except('show');
    
    // Tour Comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::get('/{comment}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        Route::post('/{comment}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{comment}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::post('/{comment}/toggle-featured', [CommentController::class, 'toggleFeatured'])->name('toggle-featured');
    });
    
    // Blog Comments
    Route::group(['prefix' => 'blog-comments', 'as' => 'blog-comments.'], function () {
        Route::get('/', [BlogCommentController::class, 'index'])->name('index');
        Route::get('/{blogComment}/edit', [BlogCommentController::class, 'edit'])->name('edit');
        Route::get('/{blogComment}/create-reply', [BlogCommentController::class, 'createReply'])->name('create-reply');
        Route::post('/{blogComment}/reply', [BlogCommentController::class, 'storeReply'])->name('store-reply');
        Route::put('/{blogComment}', [BlogCommentController::class, 'update'])->name('update');
        Route::delete('/{blogComment}', [BlogCommentController::class, 'destroy'])->name('destroy');
        Route::post('/{blogComment}/approve', [BlogCommentController::class, 'approve'])->name('approve');
        Route::post('/{blogComment}/reject', [BlogCommentController::class, 'reject'])->name('reject');
        Route::post('/{blogComment}/toggle-featured', [BlogCommentController::class, 'toggleFeatured'])->name('toggle-featured');
    });
    
    // Tour Packages
    Route::resource('tour-packages', TourPackageController::class)->except('show');
    Route::post('tour-packages/{tourPackage}/toggle-enabled', [TourPackageController::class, 'toggleEnabled'])->name('tour-packages.toggle-enabled');
    
    // Tour Advanced Pricing
    Route::group(['prefix' => 'tours/{tour}/pricing', 'as' => 'tour-pricing.'], function () {
        Route::get('/', [TourPricingController::class, 'index'])->name('index');
        
        // Date-specific pricing
        Route::post('/date', [TourPricingController::class, 'storeDatePrice'])->name('date.store');
        Route::delete('/date/{datePrice}', [TourPricingController::class, 'destroyDatePrice'])->name('date.destroy');
        Route::post('/date/bulk-import', [TourPricingController::class, 'bulkImportDates'])->name('date.bulk-import');
        
        // Duration pricing
        Route::post('/duration', [TourPricingController::class, 'storeDurationPrice'])->name('duration.store');
        Route::delete('/duration/{durationPrice}', [TourPricingController::class, 'destroyDurationPrice'])->name('duration.destroy');
        
        // Early bird discounts
        Route::post('/early-bird', [TourPricingController::class, 'storeEarlyBird'])->name('early-bird.store');
        Route::delete('/early-bird/{earlyBird}', [TourPricingController::class, 'destroyEarlyBird'])->name('early-bird.destroy');
        
        // Last minute deals
        Route::post('/last-minute', [TourPricingController::class, 'storeLastMinute'])->name('last-minute.store');
        Route::delete('/last-minute/{lastMinute}', [TourPricingController::class, 'destroyLastMinute'])->name('last-minute.destroy');
        
        // Dynamic pricing
        Route::post('/dynamic', [TourPricingController::class, 'storeDynamic'])->name('dynamic.store');
        Route::delete('/dynamic/{dynamic}', [TourPricingController::class, 'destroyDynamic'])->name('dynamic.destroy');
        
        // Tiered pricing
        Route::post('/tiered', [TourPricingController::class, 'storeTiered'])->name('tiered.store');
        Route::delete('/tiered/{tiered}', [TourPricingController::class, 'destroyTiered'])->name('tiered.destroy');
        
        // Custom pricing rules
        Route::post('/rule', [TourPricingController::class, 'storeRule'])->name('rule.store');
        Route::delete('/rule/{rule}', [TourPricingController::class, 'destroyRule'])->name('rule.destroy');
    });
    
    // Toggle pricing enabled status
    Route::post('tour-pricing/toggle/{type}/{id}', [TourPricingController::class, 'toggleEnabled'])->name('tour-pricing.toggle');
    
    //RoutePlace
});
