<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\BackendController;
use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\ExportController;
use App\Http\Controllers\Site\FaqController;
use App\Http\Controllers\Site\GiftController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\OrdersController;
use App\Http\Controllers\Site\PaymentController;
use App\Http\Controllers\Site\ProductsController;
use App\Http\Controllers\Site\ProfileController;
use App\Http\Controllers\Site\TestimonialController;
use App\Http\Controllers\Site\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Product\ReviewController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::any('/', [BackendController::class, 'page']);
    Route::any('{query}', [BackendController::class, 'page'])->where('query', '(?!(login|logout)).*');
});

Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('products/{product}/reviews', [ProductsController::class, 'reviews'])->name('site.reviews.index');
Route::get('/products/search', [ProductsController::class, 'search'])->name('site.products.search');
Route::get('/products/{category?}', [ProductsController::class, 'index'])->name('site.products.index');
Route::get('/product/{url}', [ProductsController::class, 'show'])->name('site.products.show');

Route::get('/sample/{url}', [ProductsController::class, 'sample'])->name('site.products.sample');
Route::get('/shopping-cart', [CartController::class, 'shoppingCart'])->name('site.shoppingCart');
Route::get('/faq/{category?}', [FaqController::class, 'index'])->name('site.faq.index');
Route::get('/articles/{tag?}', [BlogController::class, 'index'])->name('site.articles.index');
Route::get('/article/{url}', [BlogController::class, 'show'])->name('site.articles.show');
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('site.testimonials.index');
Route::get('/contact', [HomeController::class, 'showContact'])->name('site.contact.show');
Route::post('/contact', [HomeController::class, 'saveContact'])->name('site.contact.save');
Route::post('/subscribe', [HomeController::class, 'subscribe'])->name('site.subscribe');

Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('site.privacy-policy');
Route::get('/terms-of-service', [HomeController::class, 'termsOfService'])->name('site.terms-of-service');
Route::get('/user-guide', [HomeController::class, 'userGuide'])->name('site.user-guide');
Route::get('/affiliate', [HomeController::class, 'affiliate'])->name('site.affiliate');
Route::get('/download-instructions', [HomeController::class, 'downloadInstructions'])->name('site.download-instructions');

Route::get('/gift-card', [GiftController::class, 'index'])->name('site.gift-card.index');
Route::post('/gift-card', [GiftController::class, 'save'])->name('site.gift-card.save');

Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

Route::group(['middleware' => ['auth', 'verified'], 'namespace' => 'Site'], function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('site.profile.index');
    Route::post('/changePass', [ProfileController::class, 'changePass'])->name('site.profile.password');
    Route::post('/changeMobile', [ProfileController::class, 'changeMobile'])->name('site.profile.mobile');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('site.checkout');
    Route::post('/place-order', [PaymentController::class, 'placeOrder'])->name('site.placeOrder');
    Route::get('/update-payment', [PaymentController::class, 'updatePayment'])->name('site.updateOrderPayment');
    Route::get('/retry-payment', [PaymentController::class, 'retryPayment'])->name('site.retryPayment');
    Route::get('/thank-you', [CartController::class, 'thanks'])->name('site.thanks');

    Route::get('orders', [OrdersController::class, 'index'])->name('site.orders.index');
    Route::get('orders/{refNo}', [OrdersController::class, 'show'])->name('site.orders.display');

    Route::get('/gift-card/checkout', [GiftController::class, 'checkout'])->name('site.gift-card.checkout');
    Route::post('/place-gift-order', [PaymentController::class, 'placeGiftOrder'])->name('site.placeGiftOrder');

    Route::get('export/products', [ExportController::class, 'exportProducts'])->name('products.export');
    Route::get('export/orders', [ExportController::class, 'exportOrders'])->name('orders.export');

    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

addCartRoutes();

Auth::routes(['verify' => true]);
