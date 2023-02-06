<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Support\Collection;

/**
 * Class Coupon
 * @package App\Models
 *
 * @property string    type
 * @property string    name
 * @property string    code
 * @property int       discount
 * @property int       minAmount
 * @property int       usageLimit
 * @property int       usages
 * @property boolean   uniqueOnUser
 * @property boolean   excludeDiscounts
 * @property boolean   firstPurchase
 * @property string    startDate
 * @property string    endDate
 *
 * @property int       discountValue
 *
 * @property Product[] products
 */
class Coupon extends AbstractModel
{
    use Numbers;

    const TYPE_PERCENT = 'percent';
    const TYPE_AMOUNT  = 'amount';

    /**
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'code',
        'discount',
        'minAmount',
        'usageLimit',
        'uniqueOnUser',
        'excludeDiscounts',
        'firstPurchase',
        'startDate',
        'endDate'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'minAmount'        => 'integer',
        'usageLimit'       => 'integer',
        'usages'           => 'integer',
        'uniqueOnUser'     => 'boolean',
        'excludeDiscounts' => 'boolean',
        'firstPurchase'    => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(
                Product::class,
                'coupons_products',
                'couponId',
                'productId'
            )
            ->withTimestamps();
    }

    /**
     * @return Collection
     */
    public function getProductsIds()
    {
        return $this->products->map(function (Product $product) {
            return $product->id;
        })->flip();
    }

    /**
     * @param float $discount
     */
    public function setDiscountAttribute(float $discount)
    {
        if ($this->attributes['type'] === self::TYPE_AMOUNT) {
            $discount = $this->toIntAmount($discount);
        }

        $this->attributes['discount'] = $discount;
    }

    /**
     * @param $minAmount
     */
    public function setMinAmountAttribute($minAmount)
    {
        $this->attributes['minAmount'] = $this->toIntAmount($minAmount);
    }

    /**
     * @return int
     */
    public function incrementUsages()
    {
        return $this->increment('usages');
    }
}