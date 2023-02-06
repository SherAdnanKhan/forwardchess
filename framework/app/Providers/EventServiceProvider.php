<?php

namespace App\Providers;

use App\Events\CartItemChangedEvent;
use App\Events\ContactSavedEvent;
use App\Events\OrderCompletedEvent;
use App\Events\ProductVisitedEvent;
use App\Listeners\AddProductToBlockedListener;
use App\Listeners\CartItemChangedListener;
use App\Listeners\CleanWishlistListener;
use App\Listeners\MailchimpResetAutomationsListener;
use App\Listeners\LoginListener;
use App\Listeners\LogoutListener;
use App\Listeners\OrderGiftCardListener;
use App\Listeners\ProductVisitedListener;
use App\Listeners\RegisterMobilePurchaseListener;
use App\Listeners\SendContactMessageListener;
use App\Listeners\SendOrderConfirmationListener;
use App\Listeners\SendRegisterMessageListener;
use App\Listeners\UpdateUserListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        Verified::class => [
            SendRegisterMessageListener::class
        ],
        //            RegisterMobileUserListener::class,

        Login::class => [
            LoginListener::class,
        ],

        Logout::class => [
            LogoutListener::class
        ],

        ContactSavedEvent::class => [
            SendContactMessageListener::class,
        ],

        CartItemChangedEvent::class => [
            CartItemChangedListener::class,
        ],

        OrderCompletedEvent::class => [
            SendOrderConfirmationListener::class,
            RegisterMobilePurchaseListener::class,
            OrderGiftCardListener::class,
            UpdateUserListener::class,
            CleanWishlistListener::class,
            AddProductToBlockedListener::class,
            MailchimpResetAutomationsListener::class
        ],

        ProductVisitedEvent::class => [
            ProductVisitedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
