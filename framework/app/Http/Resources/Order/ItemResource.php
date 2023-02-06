<?php

namespace App\Http\Resources\Order;

use App\Models\Order\Item;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemResource
 * @package App\Http\Resources\Order
 */
class ItemResource extends JsonResource
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
        /** @var Item $item */
        $item = $this->resource;

        return [
            'id'        => $item->id,
            'productId' => $item->productId,
            'name'      => $item->name,
            'unitPrice' => $item->unitPrice,
            'total'     => $item->total,
        ];
    }
}
