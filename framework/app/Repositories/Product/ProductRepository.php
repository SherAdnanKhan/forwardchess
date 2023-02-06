<?php

namespace App\Repositories\Product;

use App\Assets\SortBy;
use App\Assets\WishlistReport;
use App\Builders\ProductBuilder;
use App\Common\Cache\CacheModel;
use App\Models\AbstractModel;
use App\Models\Product\Product;
use App\Repositories\AbstractModelRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductRepository
 * @package App\Repositories\Product
 */
class ProductRepository extends AbstractModelRepository
{
    /**
     * @param array       $search
     * @param SortBy|null $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $search['trashIncluded'] = isset($search['trashIncluded']) ? (bool)$search['trashIncluded'] : $this->isTrashedIncludedInSearch();
        $search['sortBy']        = $sortBy;

        return ProductBuilder::make($search)->init();
    }

    /**
     * @param Product $product
     * @param array   $categories
     *
     * @return ProductRepository
     */
    public function saveCategories(Product $product, array $categories): self
    {
        $query = 'DELETE FROM `products_categories` WHERE `productId` = ?';
        DB::delete($query, [$product->id]);

        $query = 'INSERT INTO `products_categories`(`productId`, `categoryId`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), NOW())';

        foreach ($categories as $category) {
            DB::insert($query, [$product->id, $category]);
        }

        $product->load('categories');
        $product->fireEvent('changed');

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return ProductRepository
     */
    public function clearBundleProducts(Product $product): self
    {
        $product->children()->detach();

        return $this;
    }

    /**
     * @param Product $product
     * @param array   $bundleProducts
     *
     * @return ProductRepository
     */
    public function saveBundleProducts(Product $product, array $bundleProducts): self
    {
        $query = 'DELETE FROM `product_bundles` WHERE `bundleId` = ?';
        DB::delete($query, [$product->id]);

        $query = 'INSERT INTO `product_bundles`(`bundleId`, `productId`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), NOW())';

        foreach ($bundleProducts as $bundleProduct) {
            DB::insert($query, [$product->id, $bundleProduct]);
        }

        $product->load('children');

        $product->calculateBundlePrice();
        $product->save();

        $product->fireEvent('changed');

        return $this;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function getIdFromUrl(string $url): ?string
    {
        /** @var Product $product */
        $product = $this->getResource();

        return $product->getIdFromUrl($url);
    }

    /**
     * @param WishlistReport $report
     *
     * @return array
     */
    public function wishlistProducts(WishlistReport $report): array
    {
        $builder = $this
            ->getResource()
            ->query()
            ->select([
                'products.id',
                'products.title',
                'products.price',
                'products.url',
                DB::raw('COUNT(`wishlist`.`id`) as `nrUsers`')
            ])
            ->leftJoin('wishlist', 'wishlist.productId', '=', 'products.id')
            ->groupBy('products.id', 'products.title', 'products.price', 'products.url')
            ->having(DB::raw('COUNT(`wishlist`.`id`)'), '>', 0);

        if (!empty($query = $report->getQuery())) {
            $builder = $builder->where('products.title', 'like', '%' . $query . '%');
        }

        $response = $builder
            ->orderBy($report->getSortBy(), $report->getSortDir())
            ->paginate($report->getRowsPerPage());

        return [
            'data'  => $response->items(),
            'count' => $response->total()
        ];
    }

    /**
     * @param int|string $sku
     *
     * @return AbstractModel
     */
    public function getBySKU($sku): ?AbstractModel
    {
        $sku        = (string)$sku;
        $cacheModel = CacheModel::make($this)->setEntityId($sku);
        if (!$cacheModel->exists()) {
            $cacheModel->setEntity($this->first(['sku' => $sku]))->store();
        }

        return $cacheModel->getEntity();
    }
}