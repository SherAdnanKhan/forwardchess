<?php

namespace App\Repositories\Product;

use App\Repositories\AbstractModelRepository;
use Illuminate\Support\Collection;

/**
 * Class CategoryRepository
 * @package App\Repositories\Product
 */
class CategoryRepository extends AbstractModelRepository
{
    /**
     * @return Collection
     */
    public function getActiveCategories(): Collection
    {
        $result = $this
            ->getResource()
            ->query()
            ->select('categories.id')
            ->join('products_categories', 'products_categories.categoryId', '=', 'categories.id')
            ->join('products', 'products.id', '=', 'products_categories.productId')
            ->where('products.active', '=', 1)
            ->groupBy('categories.id')
            ->get();

        $ids = [];
        foreach ($result as $item) {
            $ids[] = $item->id;
        }

        return $this
            ->getResource()
            ->query()
            ->whereIn('id', $ids)
            ->orderBy('position')
            ->get();
    }
}
