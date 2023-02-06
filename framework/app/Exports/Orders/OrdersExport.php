<?php

namespace App\Exports\Orders;

use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OrdersExport implements WithMultipleSheets
{
    /**
     * @var ProductRepository
     */
    private $orderRepository;

    /**
     * @var string
     */
    private $month;

    /**
     * OrdersExport constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param string $month
     *
     * @return OrdersExport
     */
    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function sheets(): array
    {
        $startDay = Carbon::createFromFormat('Y-m-d', $this->month . '-01')->startOfDay();
        $endDay   = Carbon::createFromFormat('Y-m-d', $this->month . '-01')->endOfMonth();
        $sheets   = [];

        $sheets[] = new OrdersSheet($this->orderRepository, $startDay, $endDay);
        $sheets[] = new ProductsSheet($this->orderRepository, $startDay, $endDay);

        return $sheets;
    }
}