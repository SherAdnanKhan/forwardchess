<?php

namespace App\Http\Resources\Order;

use App\Models\Order\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 * @package App\Http\Resources\Order
 */
class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Payment $payment */
        $payment = $this->resource;

        return [
            'id'             => $payment->id,
            'status'         => $payment->status,
            'type'           => $payment->type,
            'firstName'      => $payment->firstName,
            'lastName'       => $payment->lastName,
            'address'        => $payment->address,
            'transactionFee' => $payment->transactionFee,
            'transactionKey' => $payment->transactionKey,
            'euVatData'      => $payment->euVatData,
            'euVatEvidence'  => $payment->euVatEvidence
        ];
    }
}
