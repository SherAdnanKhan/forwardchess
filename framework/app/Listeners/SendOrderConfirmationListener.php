<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Mail\OrderCompletedMail;
use Illuminate\Support\Facades\Mail;

/**
 * Class OrderCompletedListener
 * @package App\Listeners
 */
class SendOrderConfirmationListener
{
    /**
     * Handle the event.
     *
     * @param  OrderCompletedEvent $event
     *
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->getOrder();

        Mail::to($order->user)->send(new OrderCompletedMail($order));
    }
}
