<?php

namespace App\Common;

use App\Assets\DateRange;
use App\Contracts\OrdersProcessorInterface;
use App\Mail\OrderRefundGiftCardMail;
use App\Mail\OrderSendGiftCardMail;
use App\Models\Order\Item;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Class OrdersProcessor
 * @package App\Common
 */
class OrdersProcessor implements OrdersProcessorInterface
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * OrdersProcessor constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function processPendingOrders()
    {
        Log::debug('begin ProcessOrders ' . date('Y-m-d H:i:s'));

        /** @var OrderRepository $orderRepository */
        $orderRepository = app(OrderRepository::class);

        $orders = $orderRepository->get([
            'period' => DateRange::make(null, Carbon::now()->subHour(2)),
            'status' => Order::STATUS_PENDING
        ]);

        Log::debug('retrieved ProcessOrders: ' . date('Y-m-d H:i:s') . ' -> ' . $orders->count());

        if ($orders->isEmpty()) {
            return;
        }

        $orders->each(function ($order) {
            try {
                DB::beginTransaction();

                $hasVouchers = $this->refundVouchers($order);

                $this->orderRepository->update($order, ['status' => Order::STATUS_CANCELLED]);

                DB::commit();

                if ($hasVouchers) {
                    Mail::to($order->user)
                        ->send(new OrderRefundGiftCardMail($order));
                }
            } catch (\Exception $e) {
                DB::rollBack();
            }
        });
    }

    /**
     * @param Order $order
     */
    public function sendGifts(Order $order)
    {
        $items = $order->items()->where('type', '=', Item::TYPE_GIFT)->get();
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function (Item $item) use ($order) {
            $item->gift->update(['enabled' => 1]);

            Mail::to([$item->gift->friendEmail])
                ->bcc($order->user)
                ->send(new OrderSendGiftCardMail($order, $item->gift));
        });
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    private function refundVouchers(Order $order): bool
    {
        if ($order->vouchers->isEmpty()) {
            return false;
        }

        foreach ($order->vouchers as $voucher) {
            $gift = $voucher->gift;
            $gift->setAmountAttribute($gift->amount + (int)$voucher->getRawOriginal('amount'), false);

            $gift->expireDate = max($gift->expireDate, Carbon::now()->addWeek(2));

            $gift->save();

            $voucher->refunded = true;
            $voucher->save();
        }

        return true;
    }
}