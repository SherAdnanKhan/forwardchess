<?php

namespace App\Jobs\Mailchimp;

use App\Assets\MailChimp\CartAction;
use App\Contracts\MailChimpServiceInterface;
use App\Jobs\JobSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class CartItemSyncJob
 * @package App\Jobs\Mailchimp
 */
class CartItemSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobSettings;

    /**
     * @var CartAction
     */
    private CartAction $action;

    /**
     * CartItemSyncJob constructor.
     *
     * @param CartAction $action
     */
    public function __construct(CartAction $action)
    {
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @param MailChimpServiceInterface $mailChimpService
     *
     * @throws \Exception
     */
    public function handle(MailChimpServiceInterface $mailChimpService)
    {
        try {
            $mailChimpService->syncCartItem($this->action);
        } catch (\Exception $e) {
            $ignoreCodes = [409, 503];

            if (!in_array($e->getCode(), $ignoreCodes)) {
                $this->onFail($e);
            }
        }
    }
}
