<?php

namespace App\Jobs\Mailchimp;

use App\Contracts\MailChimpServiceInterface;
use App\Jobs\JobSettings;
use App\Models\Order\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ClearAutomationsJob
 * @package App\Jobs\Mailchimp
 */
class ClearAutomationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobSettings;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * ClearCartJob constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param MailChimpServiceInterface $mailChimpService
     *
     * @throws Exception
     */
    public function handle(MailChimpServiceInterface $mailChimpService)
    {
        try {
            $mailChimpService->clearUserAutomations($this->order);
        } catch (Exception $e) {
            $ignoreCodes = [409, 503];

            if (!in_array($e->getCode(), $ignoreCodes)) {
                $this->onFail($e);
            }
        }
    }
}
