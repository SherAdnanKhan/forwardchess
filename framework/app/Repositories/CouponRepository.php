<?php

namespace App\Repositories;

use App\Assets\SortBy;
use App\Models\Coupon;
use App\Models\Order\Order;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class CouponRepository
 * @package App\Repositories
 */
class CouponRepository extends AbstractModelRepository
{
    /**
     * @param array  $search
     * @param SortBy $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $builder = $this->isTrashedIncludedInSearch() ? $this->getResource()->withTrashed() : $this->getResource()->query();

        if (!empty($search)) {
            if (isset($search['checkDate'])) {
                $search['checkDate'] = $search['checkDate']->format('Y-m-d');
                $builder             = $builder->whereRaw('`startDate` <= ? AND `endDate` >= ?', [$search['checkDate'], $search['checkDate']]);
                unset($search['checkDate']);
            }

            $builder = $builder->where($search);
        }

        if (!empty($sortBy)) {
            $builder = $builder->orderBy($sortBy->getField(), $sortBy->getDirection());
        }

        return $builder;
    }

    /**
     * @param Coupon $coupon
     * @param array  $products
     *
     * @return Coupon
     */
    public function saveProducts(Coupon $coupon, array $products): Coupon
    {
        $query = 'DELETE FROM `coupons_products` WHERE `couponId` = ?';
        DB::delete($query, [$coupon->id]);

        $query = 'INSERT INTO `coupons_products`(`couponId`, `productId`, `created_at`, `updated_at`) 
                  VALUES (?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE `updated_at` = NOW()';

        foreach ($products as $product) {
            DB::insert($query, [$coupon->id, $product]);
        }

        $coupon->load('products');
        $coupon->fireEvent('updating');

        return $coupon;
    }

    /**
     * @param Coupon $coupon
     * @param User   $user
     *
     * @return bool
     */
    public function checkUserUsage(Coupon $coupon, User $user): bool
    {
        $query = 'SELECT `id` 
                  FROM `orders` 
                  WHERE 
                    `userId` = ? AND 
                    `coupon` = ? AND 
                    `status` <> ?
                  LIMIT 0, 1;';

        $result = DB::select($query, [$user->id, $coupon->code, Order::STATUS_CANCELLED]);

        return empty($result);
    }
}