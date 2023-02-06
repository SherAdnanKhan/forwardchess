<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Jobs\Mobile\RegisterMobilePurchaseJob;

/**
 * Class CallMobileApi
 * @package App\Listeners
 */
class RegisterMobilePurchaseListener
{
    /**
     * Handle the event.
     *
     * @param OrderCompletedEvent $event
     *
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        if (isProduction()) {
            RegisterMobilePurchaseJob::dispatch($event->getOrder());
        }
    }
}
