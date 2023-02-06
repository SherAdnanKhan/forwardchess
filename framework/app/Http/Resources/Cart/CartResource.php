<?php

namespace App\Http\Resources\Cart;

use App\Models\Cart\Cart;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CartResource
 * @package App\Http\Resources\Cart
 */
class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function toResponse($request)
    {
        /** @var Cart $cart */
        $cart = $this->resource;

        return $cart->toArray();
    }
}
