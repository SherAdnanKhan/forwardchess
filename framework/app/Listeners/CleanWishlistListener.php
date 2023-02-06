<?php

namespace App\Listeners;

use App\Contracts\WishlistServiceInterface;
use App\Events\OrderCompletedEvent;
use App\Models\Order\Item;

/**
 * Class UpdateUserListener
 * @package App\Listeners
 */
class CleanWishlistListener
{
    /**
     * @var WishlistServiceInterface
     */
    private $wishlistService;

    /**
     * CleanWishlistListener constructor.
     *
     * @param WishlistServiceInterface $wishlistService
     */
    public function __construct(WishlistServiceInterface $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    /**
     * @param OrderCompletedEvent $event
     */
    public function handle(OrderCompletedEvent $event)
    {
        $event->getOrder()->items->each(function (Item $item) {
            if ($item->detail && $item->isProduct()) {
                $this->wishlistService->destroy($item->detail->id);
            }
        });
    }
}
