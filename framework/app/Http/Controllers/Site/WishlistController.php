<?php

namespace App\Http\Controllers\Site;

use App\Contracts\WishlistServiceInterface;
use App\Models\Product\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class WishlistController
 * @package App\Http\Controllers\Site
 */
class WishlistController extends AbstractSiteController
{
    /**
     * @param WishlistServiceInterface $wishlistService
     *
     * @return View
     */
    public function index(WishlistServiceInterface $wishlistService)
    {
        $wishlist = $wishlistService->list(true);

        return view('site.pages.wishlist', ['products' => $wishlist]);
    }

    /**
     * @param Request                  $request
     * @param WishlistServiceInterface $wishlistService
     *
     * @return JsonResponse
     */
    public function store(Request $request, WishlistServiceInterface $wishlistService)
    {
        return response()->json($wishlistService->store($this->getProductId($request)));
    }

    /**
     * @param Request                  $request
     * @param WishlistServiceInterface $wishlistService
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, WishlistServiceInterface $wishlistService)
    {
        return response()->json($wishlistService->destroy($this->getProductId($request)));
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    private function getProductId(Request $request): int
    {
        $productRepository = app(ProductRepository::class);

        /** @var Product $product */
        $product = $productRepository->getByIdOrFail($request->route('product'));

        return $product->id;
    }
}
