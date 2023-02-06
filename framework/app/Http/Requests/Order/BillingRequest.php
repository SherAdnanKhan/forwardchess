<?php

namespace App\Http\Requests\Order;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\AbstractFormRequest;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;

class BillingRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'show',
        'update',
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'order';

    /**
     * @param OrderRepository $orderRepository
     *
     * @return bool
     * @throws ResourceNotFoundException
     * @throws \App\Exceptions\AuthorizationException
     */
    public function authorize(OrderRepository $orderRepository)
    {
        $this->getActionName();

        /** @var Order $order */
        $order = $orderRepository->getByIdOrFail($this->route($this->ownModelParam));
        if (empty($billing = $order->billing)) {
            throw new ResourceNotFoundException();
        }

        $this->setModel($billing);

        return $this->isActionAuthorized();
    }

    /**
     * @return array
     */
    protected function globalRules(): array
    {
        return [
            'firstName' => ['string', 'max:255'],
            'lastName'  => ['string', 'max:255'],
        ];
    }
}
