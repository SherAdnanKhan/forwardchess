<?php

namespace App\Listeners;

use App\Assets\MailChimp\CartAction;
use App\Contracts\CartServiceInterface;
use App\Events\CartItemChangedEvent;
use App\Jobs\Mailchimp\CartItemSyncJob;

/**
 * Class CartItemChangedListener
 * @package App\Listeners
 */
class CartItemChangedListener
{
    /**
     * @var CartServiceInterface
     */
    private CartServiceInterface $cartService;

    /**
     * CartItemChangedListener constructor.
     *
     * @param CartServiceInterface $cartService
     */
    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @param CartItemChangedEvent $event
     *
     */
    public function handle(CartItemChangedEvent $event)
    {
        $action = CartAction::make(
            $event->getAction(),
            $this->cartService->getUser(),
            $this->cartService->get(),
            $event->getId()
        );

        if ($action->isReady()) {
            CartItemSyncJob::dispatch($action);
        }
    }
}
