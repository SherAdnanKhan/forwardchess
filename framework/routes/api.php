<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\Gcp\GcpController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\MobileController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\Order\BillingController;
use App\Http\Controllers\Api\Order\OrderController;
use App\Http\Controllers\Api\Order\PaymentController;
use App\Http\Controllers\Api\Product\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\PublisherController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TaxRateController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\WishlistsController;
use App\Http\Controllers\Api\Blog\TagController;
use App\Http\Controllers\Api\Blog\ArticleController;
use App\Http\Controllers\Api\Product\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('all/books', [ProductController::class, 'desktopPrices'])->name('products.desktop.index');

Route::middleware('mobile', 'throttle:30000,1')->group(function () {
    Route::post('createAccount', [MobileController::class, 'createAccount']);
    Route::post('updateAccount', [MobileController::class, 'updateAccount']);
});

Route::get('processQueue', [GcpController::class, 'processQueue']);

Route::middleware('auth:api', 'throttle:400,1')->group(function () {
    addResource('coupons', CouponController::class, ['table' => true]);
    addResource('gifts', GiftController::class, ['table' => true]);
    Route::get('wishlists/tables', [WishlistsController::class, 'tables'])->name('wishlists.index');
    Route::get('countries', [CountryController::class, 'getCountries'])->name('countries.index');
    Route::get('countries/{country}/states', [CountryController::class, 'getStates'])->name('states.index');

    Route::get('faq/categories', [FaqController::class, 'getCategories'])->name('faq.categories');
    addResource('tax-rates', TaxRateController::class, ['table' => true])->except([
        'store', 'destroy'
    ]);
    addResource('faq', FaqController::class, ['table' => true]);
    addResource('testimonials', TestimonialController::class, ['table' => true]);
    addResource('newsletter', NewsletterController::class, ['table' => true]);
    addResource('publishers', PublisherController::class, ['table' => true]);
    addResource('categories', CategoryController::class, ['table' => true]);

    Route::get('products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    addResource('reviews', ReviewController::class, ['table' => true])->except([
        'index', 'destroy'
    ]);

    addResource('products', ProductController::class, ['table' => true, 'restore' => true, 'routeParam' => 'product']);

    addResource('tags', TagController::class, ['table' => true]);
    addResource('articles', ArticleController::class, ['table' => true, 'restore' => true, 'routeParam' => 'article']);

    Route::get('profile', [UserController::class, 'profile']);
    Route::post('users/activate/{user}', [UserController::class, 'activate'])->name('users.activate');
    addResource('users', UserController::class, ['table' => true]);

    addResource('orders', OrderController::class, ['table' => true, 'restore' => true, 'routeParam' => 'order']);

    Route::get('orders/{order}/billing', [BillingController::class, 'show'])->name('orderBilling.show');
    Route::get('orders/{order}/payment', [PaymentController::class, 'show'])->name('orderPayment.show');
    Route::get('orders/{order}/items', [OrderController::class, 'items'])->name('order.items');

    Route::prefix('reports')->group(function () {
        Route::get('monthlySales', [ReportController::class, 'monthlySales']);
        Route::get('countrySales', [ReportController::class, 'countrySales']);
        Route::get('bestSellers', [ReportController::class, 'bestSellers']);
        Route::get('customersAnalytics', [ReportController::class, 'customersAnalytics']);
    });

    addCartRoutes();
});
