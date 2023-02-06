<?php

namespace App\Http\Resources;

use App\Models\Subscriber;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SubscriberResource
 * @package App\Http\Resources\
 */
class SubscriberResource extends JsonResource
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
        /** @var Subscriber $subscriber */
        $subscriber = $this->resource;

        return [
            'id'             => $subscriber->id,
            'user_id'        => $subscriber->user_id,
            'payment_method' => $subscriber->paypal,
            'validity'       => $subscriber->validity,
            'valid_from'     => $subscriber->valid_from,
            'valid_to'       => $subscriber->valid_to,
            'item_number'    => $subscriber->item_number,
            'txn_id'         => $subscriber->txn_id,
            'payment_gross'  => $subscriber->payment_gross,
            'currency_code'  => $subscriber->currency_code,
            'subscr_id'      => $subscriber->subscr_id,
            'payer_email'    => $subscriber->payer_email,
            'payment_status' => $subscriber->payment_status,

            'created_at'     => $subscriber->getCreatedAtFormatted(),
            'updated_at'     => $subscriber->getUpdatedAtFormatted(),
        ];
    }
}
