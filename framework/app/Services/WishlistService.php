<?php

namespace App\Services;

use App\Assets\WishlistReport;
use App\Contracts\WishlistServiceInterface;
use App\Models\Product\Product;
use App\Models\User\User;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Wishlist\WishlistDatabaseRepository;
use App\Repositories\Wishlist\WishlistRepositoryInterface;
use App\Repositories\Wishlist\WishlistSessionRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;

/**
 * Class WishlistServiceService
 * @package App\Common\Wishlist
 */
class WishlistService implements WishlistServiceInterface
{
    /**
     * @var Guard
     */
    private Guard $auth;

    /**
     * @var WishlistRepositoryInterface
     */
    private WishlistRepositoryInterface $databaseRepository;

    /**
     * @var WishlistSessionRepository
     */
    private WishlistSessionRepository $sessionRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * WishlistService constructor.
     *
     * @param Guard                      $auth
     * @param WishlistDatabaseRepository $databaseRepository
     * @param WishlistSessionRepository  $sessionRepository
     * @param ProductRepository          $productRepository
     */
    public function __construct(
        Guard $auth,
        WishlistDatabaseRepository $databaseRepository,
        WishlistSessionRepository $sessionRepository,
        ProductRepository $productRepository
    )
    {
        $this->auth               = $auth;
        $this->databaseRepository = $databaseRepository;
        $this->sessionRepository  = $sessionRepository;
        $this->productRepository  = $productRepository;

        $this->databaseRepository->setUser($this->getUser());
    }

    /**
     * @param bool $asProducts
     *
     * @return Collection
     */
    public function list($asProducts = false): Collection
    {
        $wishList = $this->getRepository()->list();
        if (!$asProducts) {
            return $wishList;
        }

        $products = $this
            ->productRepository
            ->getBuilder()
            ->whereIn('products.id', $wishList)
            ->get()
            ->mapWithKeys(function (Product $product) {
                return [$product->id => $product];
            });

        $orderedList = collect([]);
        $wishList->each(function ($productId) use ($products, $orderedList) {
            if (!empty($product = $products->get($productId))) {
                $orderedList->push($product);
            }
        });

        return $orderedList;
    }

    /**
     * @param WishlistReport $params
     *
     * @return array
     */
    public function report(WishlistReport $params): array
    {
        return $this->productRepository->wishlistProducts($params);
    }

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function store(int $productId): Collection
    {
        return $this->getRepository()->add($productId);
    }

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function destroy(int $productId): Collection
    {
        return $this->getRepository()->remove($productId);
    }

    /**
     * @param User|null $user
     *
     * @return boolean
     */
    public function onUserLogin(User $user = null)
    {
        $this->databaseRepository->setUser($user);

        $wishList = $this->sessionRepository->list();
        if ($wishList->count()) {
            $this->databaseRepository->addBulk($wishList);
        }

        return true;
    }

    /**
     * @return WishlistRepositoryInterface
     */
    private function getRepository(): WishlistRepositoryInterface
    {
        $user = $this->auth->user();

        return empty($user) ? $this->sessionRepository : $this->databaseRepository;
    }

    /**
     * @return User|null
     */
    private function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->auth->user();

        return $user;
    }
}
