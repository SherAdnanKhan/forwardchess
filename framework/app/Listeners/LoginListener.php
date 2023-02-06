<?php

namespace App\Listeners;

use App\Contracts\BlockedProductsInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\WishlistServiceInterface;
use App\Contracts\MobileGatewayInterface;
use App\Models\User\User;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    /**
     * Handle the event.
     *
     * @param Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        /** @var CartServiceInterface $cartService */
        $cartService = app(CartServiceInterface::class);

        /** @var WishlistServiceInterface $wishlistService */
        $wishlistService = app(WishlistServiceInterface::class);

        /** @var BlockedProductsInterface $blockedProductsService */
        $blockedProductsService = app(BlockedProductsInterface::class);

        $mobileGateway = app(MobileGatewayInterface::class);

        /** @var User $user */
        $user = $event->user;

        $cartService->onUserLogin($user);
        $wishlistService->onUserLogin($user);
        $blockedProductsService->onAuthenticationChanged($user);
        $mobile = $mobileGateway->getMobilePurchase($user->email);
        $blockedProductsService->blockMobileProducts($mobile);
        $mobileGateway->getFirebaseToken($user->email);
        // remove already purchased books from cart
        $cartService->checkCartOnLogin();
    }
}
