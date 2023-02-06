<?php

namespace App\Listeners;

use App\Contracts\BlockedProductsInterface;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Session;

class LogoutListener
{
    /**
     * Handle the event.
     *
     * @param Logout $event
     *
     * @return void
     */
    public function handle(Logout $event)
    {
        /** @var BlockedProductsInterface $blockedProductsService */
        $blockedProductsService = app(BlockedProductsInterface::class);

        $blockedProductsService->clear();

        // remove firebase token from session
        Session::forget('firebase_token');
    }
}
