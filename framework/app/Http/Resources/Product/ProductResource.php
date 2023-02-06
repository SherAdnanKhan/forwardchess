<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Traits\ProductTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 * @package App\Http\Resources\Product
 */
class ProductResource extends JsonResource
{
    use ProductTransformer;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->getProduct($this->resource, true);
    }
}
