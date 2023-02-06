<?php

namespace App\Services\Product;

use App\Assets\SortBy;
use App\Builders\ProductBuilder;
use App\Contracts\BlockedProductsInterface;
use App\Contracts\MobileGatewayInterface;
use App\Exceptions\CommonException;
use App\Http\Requests\Product\ProductRequest;
use App\Models\AbstractModel;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ReviewRepository;
use App\Searches\ProductSearch;
use App\Services\AbstractService;
use App\Services\Tables;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductService
 * @package App\Services\Product
 */
class ProductService extends AbstractService
{
    use Tables;

    /**
     * @var ReviewRepository
     */
    private ReviewRepository $reviewRepository;

    /**
     * @var BlockedProductsInterface
     */
    private BlockedProductsInterface $blockedProducts;

    /**
     * ProductService constructor.
     *
     * @param ProductRequest    $request
     * @param Guard             $auth
     * @param ProductRepository $repository
     * @param ReviewRepository  $reviewRepository
     */
    public function __construct(ProductRequest $request, Guard $auth, ProductRepository $repository, ReviewRepository $reviewRepository, BlockedProductsInterface $blockedProducts)
    {
        parent::__construct($request, $auth, $repository);

        $this->reviewRepository = $reviewRepository;
        $this->blockedProducts  = $blockedProducts;
        $this->setFormDataRules();
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        $searchFields = [
            'publisher' => 'publishers.name',
            'title'     => 'products.title',
            'sku'       => 'products.sku',
        ];

        $builder = $this
            ->repository
            ->getBuilder($this->initCollectionFilters());

        return $this->getTable(
            $this->request,
            $builder,
            $searchFields
        );
    }

    /**
     * @param array $filters
     *
     * @return Collection
     */
    public function allItems(array $filters = []): Collection
    {
        return $this->repository->get($this->initCollectionFilters($filters));
    }

    /**
     * @return AbstractModel
     * @throws CommonException
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        $fields         = $this->processFields();
        $categories     = $this->getCategories($fields);
        $bundleProducts = $this->getBundleProducts($fields);


        /** @var ProductRepository $repository */
        $repository = $this->repository;

        /** @var Product $model */
        $model = $repository->store($fields);
        $this->checkBundleCategory($model, $categories);

        if ($categories) {
            $repository->saveCategories($model, $categories);
        }

        // insert in the product bundles table
        if ($bundleProducts) {
            $repository->saveBundleProducts($model, $bundleProducts);
        }

        return $model;
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        /** @var Product $model */
        $model     = $this->request->getModel();
        $wasBundle = $model->isBundle;

        /** @var ProductRepository $repository */
        $repository = $this->repository;

        $fields         = $this->processFields();
        $categories     = $this->getCategories($fields);
        $bundleProducts = $this->getBundleProducts($fields);

        if (isset($fields['image']) && ($fields['image'] !== $model->image)) {
            $this->deleteImage($model->image);
        }

        if (!empty($fields) || $restore) {
            $model = $repository->update($model, $fields, $restore);
        }

        $this->checkBundleCategory($model, $categories);
        if ($model->isBundle && $bundleProducts) {
            $repository->saveBundleProducts($model, $bundleProducts);
        } else if ($wasBundle && !$model->isBundle) {
            $repository->clearBundleProducts($model);
        }

        if ($categories) {
            $repository->saveCategories($model, $categories);
        }

        return $model;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getHomeProductTabs(int $limit = 4): array
    {
        $searchParams = ['active' => 1];

        $newArrivals = $this->repository
            ->getBuilder(
                array_merge(['specialFilter' => ProductBuilder::FILTER_NEW_ARRIVALS], $searchParams),
                SortBy::make('publishDate', 'desc')
            )
            ->take($limit)
            ->get();

        $comingSoon = $this->repository
            ->getBuilder(array_merge(['specialFilter' => ProductBuilder::FILTER_COMING_SOON], $searchParams))
            ->inRandomOrder()
            ->take($limit)
            ->get();

//        $existingProducts = $newArrivals->merge($comingSoon)->mapWithKeys(function ($product) {
//            return [$product->id => $product];
//        })->keys();
//
//        $featured = $this->repository
//            ->getBuilder($searchParams)
//            ->whereNotIn('products.id', $existingProducts)
//            ->inRandomOrder()
//            ->take($limit)
//            ->get();

        return [
            'newArrivals' => $newArrivals,
            'comingSoon'  => $comingSoon
        ];
    }

    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getBestSellers(int $limit = 8): Collection
    {
        return $this->repository
            ->getBuilder(['active' => 1], SortBy::make('totalSales', 'desc'))
            ->take($limit)
            ->get();
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getMostWished(int $limit = 4): Collection
    {
        return $this->repository
            ->getBuilder(['active' => 1])
            ->addSelect(DB::raw('COUNT(products.id) as wishedCount'))
            ->join('wishlist', 'products.id', '=', 'wishlist.productId')
            ->groupBy('products.id')
            ->orderByDesc('wishedCount')
            ->take($limit)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getBestOfYear(): Collection
    {
        $skus = [
            'com.forwardchess.studychessonyourown532',
            'com.forwardchess.coffeehouse1e4volumeoneqc95',
            'com.forwardchess.demfasttrackedition557',
            'com.forwardchess.siliconroadtochessimprovement571'
        ];

        return $this->repository
            ->getBuilder(['active' => 1])
            ->whereIn('sku', $skus)
            ->get();
    }

    /**
     * @param int $limit
     *
     * @return Collection
     */
    public function getOffers(int $limit = 5): Collection
    {
        $allProducts = $this->repository
            ->getBuilder(['active' => 1], SortBy::make('totalSales', 'desc'))
            ->where('discount', '>', 0)
            ->get();

        $products = collect([]);
        foreach ($allProducts as $product) {
            if ($products->count() === $limit) {
                break;
            }

            if (!$product->hasDiscount()) {
                continue;
            }

            $products->push($product);
        }

        return $products;
    }

    public function getRelatedProducts(Product $product): array
    {
        $categoriesIds = $product->categories->map(function ($item) {
            return $item->id;
        })->toArray();

        $sameCategory = $this->repository
            ->getBuilder(
                ['excludedId' => $product->id, 'categoriesIds' => $categoriesIds, 'active' => 1],
                SortBy::make('totalSales', 'desc')
            )
            ->take(10)
            ->get();

        $samePublisher = $this->repository
            ->getBuilder(
                ['excludedId' => $product->id, 'publisherId' => $product->publisherId, 'active' => 1],
                SortBy::make('totalSales', 'desc')
            )
            ->take(10)
            ->get();

        return [
            'sameCategory'  => $sameCategory,
            'samePublisher' => $samePublisher,
        ];
    }

    /**
     * @return ProductService
     */
    protected function setFormDataRules(): self
    {
        $this->setReplacedData([
            'image' => function ($image, $attributes) {
                return $this->saveImage($attributes['sku'], $image);
            },
        ]);

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
        /** @var ProductSearch $search */
        $search = app(ProductSearch::class, ['data' => $this->request->all()]);

        return array_merge($search->getFilters(), $filters);
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return $this->getRequestFields($this->request, [
            'author'            => null,
            'publisherId'       => null,
            'categories'        => null,
            'sku'               => null,
            'title'             => '',
            'description'       => '',
            'price'             => null,
            'discountType'      => Product::TYPE_AMOUNT,
            'discount'          => null,
            'discountStartDate' => null,
            'discountEndDate'   => null,
            'nrPages'           => 0,
            'publishDate'       => null,
            'position'          => 0,
            'active'            => false,
            'image'             => null,
            'level'             => null,
            'isBundle'          => false,
            'bundleProducts'    => null,
        ]);
    }

    /**
     * @param string $name
     * @param string $content
     *
     * @return string
     */
    private function saveImage(string $name, string $content): string
    {
        [$type, $imageData] = explode(';', $content);
        [, $extension] = explode('/', $type);
        [, $imageData] = explode(',', $imageData);

        $imageName = str_slug($name . '-' . time()) . '.' . $extension;
        $imageData = base64_decode($imageData);

        Storage::disk('public')->put('products/' . $imageName, $imageData);

        return $imageName;
    }

    /**
     * @param string|null $name
     *
     * @return ProductService
     */
    private function deleteImage(string $name = null): self
    {
        if (!empty($name)) {
            Storage::disk('public')->delete('products/' . $name);
        }

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return mixed|null
     */
    private function getCategories(array &$fields)
    {
        $categories = $fields['categories'] ?? null;

        unset($fields['categories']);

        return $categories;
    }

    /**
     * @param array $fields
     *
     * @return mixed|null
     */
    private function getBundleProducts(array &$fields)
    {
        $bundleProducts = $fields['bundleProducts'] ?? null;
        // if given array contains all the parameters
        if ($bundleProducts && count($bundleProducts) > 0 && isset($bundleProducts[0]['id'])) {
            $bundleProducts = array_column($bundleProducts, 'id');
        }
        unset($fields['bundleProducts']);

        return $bundleProducts;
    }

    /**
     * @param Product $product
     * @param         $categories
     *
     * @return array|mixed
     */
    private function checkBundleCategory(Product $product, &$categories)
    {
        $bundleCategoryId = Category::getBundlesCategory()->id;

        if ($product->isBundle) {
            if (!$categories) {
                $categories = [$bundleCategoryId];
            } else if (!in_array($bundleCategoryId, $categories)) {
                $categories[] = $bundleCategoryId;
            }
        } else {
            // remove the bundle category
            $pos = array_search($bundleCategoryId, $categories);
            // Remove from array
            if ($pos) {
                unset($categories[$pos]);
            }
        }

        return $categories;
    }

    public function getBundleProductsData(Product $product): array
    {
        $bundleProducts = $product->children->map(function ($item) {
            return $this->repository
                ->getBuilder(
                    ['id' => $item->productId],
                // SortBy::make('totalSales', 'desc')
                )
                ->first();
        });

        return [
            'bundleProducts' => $bundleProducts,
        ];
    }

    /**
     * @param array                  $skuList
     * @param MobileGatewayInterface $mobileGateway
     *
     * @return array|mixed
     */
    public function getRecommendedBooks(array $skuList, MobileGatewayInterface $mobileGateway)
    {
        $recommendedBooks = $mobileGateway->getRecommendedBooks($skuList);
        $recommendedSkus  = collect($recommendedBooks)->pluck('name');

        $products = $this->repository
            ->getBuilder(['active' => 1])
            ->whereIn('sku', $recommendedSkus)
            ->get();

        foreach ($products as $key => $product) {
            if ($this->blockedProducts->hasProduct($product->id)) {
                $products->forget($key);
            }
        }

        return $products;
    }

}
