<?php

namespace App\Listeners;

use App\Jobs\Mobile\RegisterMobileUserJob;
use Illuminate\Auth\Events\Verified;

/**
 * Class CallMobileApi
 * @package App\Listeners
 */
class RegisterMobileUserListener
{
    /**
     * Handle the event.
     *
     * @param Verified $event
     *
     * @return void
     */
    public function handle(Verified $event)
    {
        if (isProduction()) {
            RegisterMobileUserJob::dispatch($event->user);
        }
    }
}
