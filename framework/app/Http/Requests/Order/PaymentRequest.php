<?php

namespace App\Http\Requests\Order;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\AbstractFormRequest;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;

class PaymentRequest extends AbstractFormRequest
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
        if (empty($payment = $order->payment)) {
            throw new ResourceNotFoundException;
        }

        $this->setModel($payment);

        return $this->isActionAuthorized();
    }

    /**
     * @return array
     */
    protected function globalRules(): array
    {
        return [
            'status'         => ['string', 'max:255'],
            'type'           => ['string', 'max:255'],
            'firstName'      => ['string', 'max:255'],
            'lastName'       => ['string', 'max:255'],
            'address'        => ['string', 'max:255'],
            'transactionFee' => ['string', 'max:255'],
            'transactionKey' => ['string', 'max:255'],
            'euVatData'      => ['string'],
            'euVatEvidence'  => ['string'],
        ];
    }
}
