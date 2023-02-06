<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\CouponTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class CouponCollection
 * @package App\Http\Resources\Product
 */
class CouponCollection extends ResourceCollection
{
    use CouponTransformer;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($couponResource) {
            return $this->getCoupon($couponResource->resource);
        })->toArray();
    }
}
