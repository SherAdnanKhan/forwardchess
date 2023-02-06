<?php

namespace App\Common;

use App\Contracts\MobileGatewayInterface;
use App\Contracts\SyncOrdersProcessorInterface;
use App\Exceptions\CommonException;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;
use App\Repositories\User\UserRepository;

/**
 * Class OrdersProcessor
 * @package App\Common
 */
class SyncOrdersProcessor implements SyncOrdersProcessorInterface
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var MobileGatewayInterface
     */
    private MobileGatewayInterface $mobileGateway;

    /**
     * SyncOrdersProcessor constructor.
     *
     * @param OrderRepository        $orderRepository
     * @param UserRepository         $userRepository
     * @param MobileGatewayInterface $mobileGateway
     */
    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository, MobileGatewayInterface $mobileGateway)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository  = $userRepository;
        $this->mobileGateway   = $mobileGateway;
    }

    /**
     * @return void
     * @throws CommonException
     */
    public function syncOrders()
    {
        $users = $this->userRepository->get(['active' => 1, 'ordersSynced' => false]);

        foreach ($users as $user) {
            $orders = $this->orderRepository->getBuilder([
                'userId' => $user->id,
                'status' => Order::STATUS_COMPLETED
            ]);

            if (!$orders->count()) {
                continue;
            }

            $data = $this->mobileGateway->getMobilePurchase($user->email);

            if (count($data) && $orders->count() === count($data)) {
                continue;
            } elseif (count($data)) {
                // orderIds saved at GCP db
                $orderIds = collect($data)->pluck('transactionId');

                // get orderIds which aren't present at GCP db
                $orderIdsToBeSynced = array_diff($orders->pluck('id')->toArray(), $orderIds->toArray());

                $orders = $orders->whereIn('orders.id', $orderIdsToBeSynced)->get();
            } else {
                $orders = $orders->get();
            }

            $orders->each(function ($order) {
                $this->mobileGateway->registerWebPurchase($order);
            });
        }
    }
}
