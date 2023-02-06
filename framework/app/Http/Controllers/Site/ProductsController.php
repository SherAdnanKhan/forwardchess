<?php

namespace App\Http\Controllers\Site;

use App\Assets\ControllerSiteData;
use App\Assets\SortBy;
use App\Builders\ProductBuilder;
use App\Common\DataCollector;
use App\Exceptions\CommonException;
use App\Http\Controllers\Site\Traits\ProductsListing;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ReviewRepository;
use App\Services\Product\ProductService;
use App\Services\Product\ReviewService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductsController
 * @package App\Http\Controllers\Site
 */
class ProductsController extends AbstractSiteController
{
    use ProductsListing;

    /**
     * @var ProductService|null
     */
    private ?ProductService $productService = null;

    /**
     * @var Category|null
     */
    private ?Category $category = null;

    /**
     * @var ControllerSiteData
     */
    private ControllerSiteData $globalData;

    /**
     * @param ProductService $productService
     * @param DataCollector  $dataCollector
     * @param string|null    $categoryName
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function index(ProductService $productService, DataCollector $dataCollector, string $categoryName = null)
    {
        $this->globalData     = $dataCollector->getGlobalData();
        $this->productService = $productService;

        if (!$this->resolveCategory($categoryName)) {
            return redirect()->route('site.products.index');
        }

        $products = $this->getProducts();

        if (isset($this->filters['title']) && ($products->total() === 1)) {
            return redirect(route('site.products.show', $products[0]->url) . '?section=Search');
        }

        $this->addViewData([
            'offers'  => $this->productService->getOffers(),
            'section' => isset($this->filters['title']) ? 'Search' : 'Products'
        ]);

        return view('site.pages.products', $this->viewData);
    }

    /**
     * @param Request          $request
     * @param ProductService   $productService
     * @param ReviewRepository $reviewRepository
     *
     * @return Application|Factory|View
     */
    public function show(Request $request, ProductService $productService, ReviewRepository $reviewRepository)
    {
        $this->productService = $productService;

        /** @var Product $product */
        $product         = $this->productService->getRequest()->getModel();
        $relatedProducts = $this->productService->getRelatedProducts($product);

        $this->addViewData([
            'product'       => $product,
            'sameCategory'  => $relatedProducts['sameCategory'],
            'samePublisher' => $relatedProducts['samePublisher'],
            'children'      => $product->isBundle ? $product->children : [],
            'review'        => [
                'isAllowed' => Auth::check() && $reviewRepository->canGiveReview($product->id, Auth::id()),
                'nickname'  => $request->user() ? $request->user()->getNickname() : null,
            ],
            'section'       => $request->query->get('section') ?? 'Direct',
        ]);
        return view('site.pages.product', $this->viewData);
    }

    /**
     * @param ProductService $productService
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function sample(ProductService $productService)
    {
        $this->productService = $productService;

        /** @var Product $product */
        $product = $this->productService->getRequest()->getModel();
        if ($product->denyBuy()) {
            return redirect()->route('site.products.index');
        }

//        event(new ProductVisitedEvent($product, Auth::user()));

        $this->addViewData([
            'product' => $product
        ]);

        return view('site.pages.sample', $this->viewData);
    }

    /**
     * @param Request        $request
     * @param ProductService $productService
     *
     * @return JsonResponse
     */
    public function search(Request $request, ProductService $productService): JsonResponse
    {
        $query    = $request->input('query');
        $products = empty($query)
            ? []
            : $productService
                ->paginate(['title' => $query, 'active' => 1], 10, SortBy::make('title', 'asc'))
                ->map(function ($product) {
                    return [
                        'author' => $product->author,
                        'title'  => $product->title,
                    ];
                });

        return response()->json($products);
    }

    /**
     * @param ReviewService     $reviewService
     * @param ProductRepository $productRepository
     * @param string            $productId
     *
     * @return array
     * @throws CommonException
     */
    public function reviews(ReviewService $reviewService, ProductRepository $productRepository, string $productId): array
    {
        $product = $productRepository->getById($productId);
        $reviews = $reviewService->getReviews($product);

        return [
            'reviews'     => $reviews['reviews'],
            'userReview'  => $reviews['userReview'],
            'lastPage'    => 1,
            'total'       => count($reviews['reviews']),
            'rating'      => $reviews['rating'],
        ];
    }

    /**
     * @param string|null $categoryUrl
     *
     * @return bool|null
     */
    private function resolveCategory(string $categoryUrl = null): ?bool
    {
        if ($categoryUrl === ProductBuilder::FILTER_NEW_ARRIVALS) {
            $this->setSpecialFilter($categoryUrl);
            return true;
        }

        if (!empty($categoryUrl)) {
            $category = $this->globalData->categories->filter(function ($category) use ($categoryUrl) {
                return ($category->url === $categoryUrl);
            })->first();

            if (empty($category)) {
                return false;
            }

            $this->category = $category;
        }

        return true;
    }
}
