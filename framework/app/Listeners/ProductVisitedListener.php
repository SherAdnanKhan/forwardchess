<?php

namespace App\Listeners;

use App\Contracts\MailChimpServiceInterface;
use App\Events\ProductVisitedEvent;
use Exception;

/**
 * Class ProductVisitedListener
 * @package App\Listeners
 */
class ProductVisitedListener
{
    /**
     * @var MailChimpServiceInterface
     */
    private MailChimpServiceInterface $mailChimpService;

    /**
     * ProductVisitedListener constructor.
     *
     * @param MailChimpServiceInterface $mailChimpService
     */
    public function __construct(MailChimpServiceInterface $mailChimpService)
    {
        $this->mailChimpService = $mailChimpService;
    }

    /**
     * @param ProductVisitedEvent $event
     */
    public function handle(ProductVisitedEvent $event)
    {
        if (isProduction() && !empty($user = $event->getUser())) {
            try {
                $this->mailChimpService->productAbandoned($user, $event->getProduct());
            } catch (Exception $e) {
                dd($e);
            }
        }
    }
}
