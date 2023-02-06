<?php

namespace App\Common;

use App\Assets\ControllerSiteData;
use App\Contracts\CartServiceInterface;
use App\Contracts\WishlistServiceInterface;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\WishlistService;

/**
 * Class SiteDataCollector
 * @package App\Common
 */
class DataCollector
{
    /**
     * @var CartServiceInterface
     */
    private $cartService;

    /**
     * @var WishlistService
     */
    protected $wishlistService;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ControllerSiteData
     */
    protected $globalData;

    /**
     * DataCollector constructor.
     *
     * @param CartServiceInterface     $cartService
     * @param WishlistServiceInterface $wishlistService
     * @param CategoryRepository       $categoryRepository
     * @param ProductRepository        $productRepository
     */
    public function __construct(
        CartServiceInterface $cartService,
        WishlistServiceInterface $wishlistService,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    )
    {
        $this->cartService        = $cartService;
        $this->wishlistService    = $wishlistService;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository  = $productRepository;
        $this->collectGlobalData();
    }

    /**
     * @return ControllerSiteData
     */
    public function getGlobalData(): ControllerSiteData
    {
        return $this->globalData;
    }

    /**
     * @return DataCollector
     */
    protected function collectGlobalData(): DataCollector
    {
        $data = $this->getProductsRelatedData();

        $data['cart']     = $this->cartService->get();
        $data['wishlist'] = $this->wishlistService->list();

        $this->globalData = ControllerSiteData::make($data);

        return $this;
    }

    /**
     * @return array
     */
    private function getProductsRelatedData(): array
    {
        $publishers = collect([]);
        $categories = $this->categoryRepository->getActiveCategories();
        $products   = $this->productRepository->get(['active' => 1]);

        foreach ($products as $product) {
            if (!isset($publishers[$product->publisherId])) {
                $publishers[$product->publisherId] = $product->publisher;
            }
        }

        $publishers = $publishers->sortBy('position');

        return [
            'products'   => $products,
            'publishers' => $publishers,
            'categories' => $categories,
        ];
    }
}
