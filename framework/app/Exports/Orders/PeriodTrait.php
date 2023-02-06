<?php

namespace App\Exports\Orders;

use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Carbon\Carbon;

trait PeriodTrait
{
    /**
     * @var ProductRepository
     */
    private $orderRepository;

    /**
     * @var Carbon
     */
    private $startDay;

    /**
     * @var Carbon
     */
    private $endDay;

    /**
     * OrdersExport constructor.
     *
     * @param OrderRepository $orderRepository
     * @param Carbon          $startDay
     * @param Carbon          $endDay
     */
    public function __construct(OrderRepository $orderRepository, Carbon $startDay, Carbon $endDay)
    {
        $this->orderRepository = $orderRepository;
        $this->startDay        = $startDay;
        $this->endDay          = $endDay;
    }
}