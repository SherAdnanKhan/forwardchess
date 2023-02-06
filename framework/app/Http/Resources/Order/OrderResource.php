<?php

namespace App\Http\Resources\Order;

use App\Models\Order\Order;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 * @package App\Http\Resources\Order
 */
class OrderResource extends JsonResource
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
        /** @var Order $order */
        $order = $this->resource;

        return [
            'id'             => $order->id,
            'userId'         => $order->userId,
            'status'         => $order->status,
            'paymentMethod'  => $order->paymentMethod,
            'currency'       => $order->currency,
            'subTotal'       => (string)$order->subTotal,
            'coupon'         => $order->coupon,
            'discount'       => (string)$order->discount,
            'taxRateCountry' => $order->taxRateCountry,
            'taxRateAmount'  => (string)$order->taxRateAmount,
            'taxAmount'      => (string)$order->taxAmount,
            'total'          => (string)$order->total,
            'refNo'          => $order->refNo,
            'allowDownload'  => $order->allowDownload,
            'email'          => $order->email,
            'fullName'       => $order->fullName,

            $this->mergeWhen($request->user() && $request->user()->isAdmin, [
                'completedDate' => $order->getCompletedDateFormatted(),
                'paidDate'      => $order->getPaidDateFormatted(),
                'deleted_at'    => $order->getDeletedAtFormatted(),
            ]),

            'created_at' => $order->getCreatedAtFormatted(),
            'updated_at' => $order->getUpdatedAtFormatted(),
        ];
    }
}
