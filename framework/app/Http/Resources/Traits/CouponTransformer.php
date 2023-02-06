<?php

namespace App\Http\Resources\Traits;

use App\Models\Coupon;

trait CouponTransformer
{
    protected function getCoupon(Coupon $coupon, bool $fullMode = false): array
    {
        $discount = ($coupon->type === Coupon::TYPE_AMOUNT)
            ? $coupon->toFloatAmount($coupon->discount)
            : $coupon->discount;

        $data = [
            'id'               => $coupon->id,
            'type'             => $coupon->type,
            'name'             => $coupon->name,
            'code'             => $coupon->code,
            'discount'         => (string)$discount,
            'minAmount'        => toFloatAmount($coupon->minAmount),
            'usageLimit'       => $coupon->usageLimit,
            'uniqueOnUser'     => $coupon->uniqueOnUser,
            'excludeDiscounts' => $coupon->excludeDiscounts,
            'firstPurchase'    => $coupon->firstPurchase,
            'startDate'        => $coupon->startDate,
            'endDate'          => $coupon->endDate,
        ];

        if ($fullMode) {
            $products = $coupon->products->map(function ($product) {
                return $product->id;
            });

            $data = array_merge($data, [
                'products' => $products,
            ]);
        }

        $data = array_merge($data, [
            'created_at' => $coupon->getCreatedAtFormatted(),
            'updated_at' => $coupon->getUpdatedAtFormatted()
        ]);

        return $data;
    }
}