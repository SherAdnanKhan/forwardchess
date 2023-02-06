<?php

namespace App\Http\Resources\Order;

use App\Models\Order\Billing;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 * @package App\Http\Resources\Order
 */
class BillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Billing $billing */
        $billing = $this->resource;

        return [
            'id'         => $billing->id,
            'firstName'  => $billing->firstName,
            'lastName'   => $billing->lastName,
            'email'      => $billing->email,
            'phone'      => $billing->phone,
            'company'    => $billing->company,
            'address1'   => $billing->address1,
            'address2'   => $billing->address2,
            'city'       => $billing->city,
            'state'      => $billing->state,
            'postcode'   => $billing->postcode,
            'country'    => $billing->country,
            'location'   => $billing->location ? json_decode($billing->location) : null,
            'created_at' => $billing->getCreatedAtFormatted(),
            'updated_at' => $billing->getUpdatedAtFormatted(),
        ];
    }
}
