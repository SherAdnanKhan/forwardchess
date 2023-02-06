<?php

namespace App\Repositories\Wishlist;

use App\Exceptions\CommonException;
use App\Models\Product\Product;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;

/**
 * Class WishlistSessionRepository
 * @package App\Repositories\Wishlist
 */
class WishlistSessionRepository implements WishlistRepositoryInterface
{
    /**
     * @var Store
     */
    private $session;

    /**
     * WishlistSessionRepository constructor.
     *
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        $wishlist = $this->session->get('wishlist', []);

        return collect($wishlist);
    }

    /**
     * @param int $productId
     *
     * @return Collection
     * @throws CommonException
     */
    public function add(int $productId): Collection
    {
        $wishlist = $this->list();
        if ($wishlist->contains($productId)) {
            throw new CommonException('Product is already on your wishlist!');
        }

        $wishlist->push($productId);

        return $this->save($wishlist);
    }

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function remove(int $productId): Collection
    {
        $wishlist = $this->list()->reject(function ($item) use ($productId) {
            return ($item === $productId);
        });;

        return $this->save($wishlist);
    }

    /**
     * @param Collection $list
     *
     * @return Collection
     */
    private function save(Collection $list): Collection
    {
        $this->session->put('wishlist', $list);

        return $list;
    }
}
