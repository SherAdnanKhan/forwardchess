<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\CouponTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CouponResource
 * @package App\Http\Resources\Product
 */
class CouponResource extends JsonResource
{
    use CouponTransformer;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->getCoupon($this->resource, true);
    }
}
