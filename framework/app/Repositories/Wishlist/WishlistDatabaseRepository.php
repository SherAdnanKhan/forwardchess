<?php

namespace App\Repositories\Wishlist;

use App\Assets\SortBy;
use App\Exceptions\CommonException;
use App\Models\User\User;
use App\Models\Wishlist;
use App\Repositories\AbstractModelRepository;
use Illuminate\Support\Collection;

/**
 * Class WishlistDatabaseRepository
 * @package App\Repositories\Wishlist
 */
class WishlistDatabaseRepository extends AbstractModelRepository implements WishlistRepositoryInterface
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return $this
            ->getBuilder(['userId' => $this->user->id], SortBy::make('created_at', 'ASC'))
            ->get()
            ->map(function (Wishlist $item) {
                return $item->productId;
            });
    }

    /**
     * @param int $productId
     *
     * @return Collection
     * @throws CommonException
     */
    public function add(int $productId): Collection
    {
        $this->store([
            'userId'    => $this->user->id,
            'productId' => $productId
        ]);

        return $this->list();
    }

    /**
     * @param int $productId
     *
     * @return Collection
     */
    public function remove(int $productId): Collection
    {
        $this
            ->getBuilder([
                'userId'    => $this->user->id,
                'productId' => $productId
            ])
            ->delete();

        return $this->list();
    }

    /**
     * @param Collection $list
     *
     * @return $this
     */
    public function addBulk(Collection $list): WishlistDatabaseRepository
    {
        $list->map(function ($productId) {
            return [
                'userId'    => $this->user->id,
                'productId' => $productId
            ];
        })->each(function ($data) {
            $this->getResource()->updateOrCreate($data);
        });

        return $this;
    }

    /**
     * @param User|null $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }
}
