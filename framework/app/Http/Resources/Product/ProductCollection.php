<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Traits\ProductTransformer;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class ProductCollection
 * @package App\Http\Resources\Product
 */
class ProductCollection extends ResourceCollection
{
    use ProductTransformer;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($productResource) {
            return $this->getProduct($productResource->resource);
        })->toArray();
    }
}
