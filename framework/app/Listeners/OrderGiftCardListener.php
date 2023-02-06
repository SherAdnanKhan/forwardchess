<?php

namespace App\Listeners;

use App\Contracts\OrdersProcessorInterface;
use App\Events\OrderCompletedEvent;

/**
 * Class OrderGiftCardListener
 * @package App\Listeners
 */
class OrderGiftCardListener
{
    /**
     * @var OrdersProcessorInterface
     */
    private $ordersProcessor;

    /**
     * OrderGiftCardListener constructor.
     *
     * @param OrdersProcessorInterface $ordersProcessor
     */
    public function __construct(OrdersProcessorInterface $ordersProcessor)
    {
        $this->ordersProcessor = $ordersProcessor;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCompletedEvent $event
     *
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        $this->ordersProcessor->sendGifts($event->getOrder());
    }
}
