<?php

namespace App\Jobs\Mobile;

use App\Contracts\MobileGatewayInterface;
use App\Jobs\JobSettings;
use App\Models\Order\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class RegisterMobilePurchaseJob
 * @package App\Jobs\Mobile
 */
class RegisterMobilePurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobSettings;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * UpdateMobileUser constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @param MobileGatewayInterface $mobileGateway
     *
     * @throws Exception
     */
    public function handle(MobileGatewayInterface $mobileGateway)
    {
        Log::info(__METHOD__ . " -- Calling mobile web purchase api for order : ", $this->order->toArray());

        try {
            $mobileGateway->registerWebPurchase($this->order);
            Log::info(__METHOD__ . " -- Success: mobile web purchase api called successfully for order_id " . $this->order->id);
        } catch (Exception $e) {
            Log::info(__METHOD__ . " -- Status code : " [$e->getCode()]);
            Log::error(__METHOD__ . " -- Failure: mobile web purchase api failed for order_id " . $this->order->id);
            Log::error(__METHOD__ . " -- Exception : " [$e]);

            $ignoreCodes = [409];

            if (!in_array($e->getCode(), $ignoreCodes)) {
                $this->onFail($e);
            }
        }
    }
}
