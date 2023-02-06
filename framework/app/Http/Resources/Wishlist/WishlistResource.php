<?php

namespace App\Http\Resources\Wishlist;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CartResource
 * @package App\Http\Resources\Cart
 */
class WishlistResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Wishlist $wishlist */
        $wishlist = $this->resource;

        return $wishlist->toArray();
    }
}
