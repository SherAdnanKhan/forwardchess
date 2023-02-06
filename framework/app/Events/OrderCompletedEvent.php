<?php

namespace App\Events;

use App\Models\Order\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderCompletedListener
 * @package App\Events
 */
class OrderCompletedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * @var array
     */
    private array $data = [];

    /**
     * OrderCompletedEvent constructor.
     *
     * @param Order $order
     * @param array $data
     */
    public function __construct(Order $order, array $data = [])
    {
        $this->order = $order;
        $this->data  = $data;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
