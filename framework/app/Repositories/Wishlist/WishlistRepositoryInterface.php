<?php

namespace App\Repositories\Wishlist;

use App\Models\Product\Product;
use Illuminate\Support\Collection;

/**
 * Interface WishlistRepositoryInterface
 * @package App\Repositories\Wishlist
 */
interface WishlistRepositoryInterface
{
    /**
     * @return Collection
     */
    public function list(): Collection;

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function add(int $productId): Collection;

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function remove(int $productId): Collection;
}