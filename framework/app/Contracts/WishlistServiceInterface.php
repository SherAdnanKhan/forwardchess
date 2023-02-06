<?php

namespace App\Contracts;

use App\Assets\WishlistReport;
use Illuminate\Support\Collection;

/**
 * Interface WishlistServiceInterface
 * @package App\Contracts
 */
interface WishlistServiceInterface
{
    /**
     * @param bool $asProducts
     *
     * @return Collection
     */
    public function list($asProducts = true): Collection;

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function store(int $productId): Collection;

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function destroy(int $productId): Collection;

    /**
     * @param WishlistReport $params
     *
     * @return array
     */
    public function report(WishlistReport $params): array;
}