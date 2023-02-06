<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Jobs\Mailchimp\ClearAutomationsJob;

/**
 * Class MailchimpResetAutomationsListener
 * @package App\Listeners
 */
class MailchimpResetAutomationsListener
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
            ClearAutomationsJob::dispatch($event->getOrder());
        }
    }
}
