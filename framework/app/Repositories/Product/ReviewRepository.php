<?php

namespace App\Repositories\Product;

use App\Assets\SortBy;
use App\Builders\ReviewBuilder;
use App\Contracts\CacheStorageInterface;
use App\Models\AbstractModel;
use App\Repositories\AbstractModelRepository;
use App\Repositories\Order\OrderRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ReviewRepository
 * @package App\Repositories\Product
 */
class ReviewRepository extends AbstractModelRepository
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @param AbstractModel         $resource
     * @param CacheStorageInterface $cache
     * @param OrderRepository       $orderRepository
     */
    public function __construct(AbstractModel $resource, CacheStorageInterface $cache, OrderRepository $orderRepository)
    {
        parent::__construct($resource, $cache);

        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array       $search
     * @param SortBy|null $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $search['sortBy'] = $sortBy;

        return ReviewBuilder::make($search)->init();
    }

    /**
     * @param int $productId
     *
     * @return int
     */
    public function getProductRating(int $productId): int
    {
        $response = $this
            ->getResource()
            ->query()
            ->select(DB::raw('avg(rating) as rating'))
            ->where('productId', $productId)
            ->where('approved', 1)
            ->first();

        return $response->rating ? toIntAmount($response->rating) : 0;
    }

    /**
     * @param int      $productId
     * @param int|null $userId
     *
     * @return bool
     */
    public function canGiveReview(int $productId, int $userId = null): bool
    {
        if (empty($userId)) {
            return false;
        }

        if (!$this->orderRepository->userHasPurchase($userId, $productId)) {
            return false;
        }

        $ratingsCount = $this->getApprovedRatingsTotal($userId, $productId);

        return ($ratingsCount < 1);
    }

    /**
     * @param int $productId
     * @param int $userId
     *
     * @return int
     */
    private function getApprovedRatingsTotal(int $userId, int $productId): int
    {
        $response = $this
            ->getResource()
            ->query()
            ->select(DB::raw('count(id) as total'))
            ->where('productId', $productId)
            ->where('userId', $userId)
            ->where('approved', 1)
            ->groupBy('productId', 'userId')
            ->first();

        return $response ? $response->total : 0;
    }
}
