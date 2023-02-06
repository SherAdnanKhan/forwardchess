<?php

namespace App\Listeners;

use App\Jobs\Mailchimp\UserRegistrationJob;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

/**
 * Class SendRegisterMessageListener
 * @package App\Listeners
 */
class SendRegisterMessageListener
{
    public function handle(Verified $event)
    {
        Log::debug('SendRegisterMessageListener');

        UserRegistrationJob::dispatch($event->user);
    }
}
