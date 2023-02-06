<?php

namespace App\Listeners;

use App\Contracts\BlockedProductsInterface;
use App\Events\OrderCompletedEvent;

/**
 * Class UpdateUserListener
 * @package App\Listeners
 */
class AddProductToBlockedListener
{
    /**
     * @var BlockedProductsInterface
     */
    private BlockedProductsInterface $blockedProducts;

    /**
     * AddProductToBlockedListener constructor.
     *
     * @param BlockedProductsInterface $blockedProducts
     */
    public function __construct(BlockedProductsInterface $blockedProducts)
    {
        $this->blockedProducts = $blockedProducts;
    }

    /**
     * @param OrderCompletedEvent $event
     */
    public function handle(OrderCompletedEvent $event)
    {
        $items = [];
        foreach ($event->getOrder()->blockItems as $item) {
            if ($item->detail && $item->isProduct()) {
                $items[] = $item->productId;
            }
        }

        $this->blockedProducts->store($items);
    }
}
