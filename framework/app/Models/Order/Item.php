<?php

namespace App\Models\Order;

use App\Models\AbstractModel;
use App\Models\Gift;
use App\Models\Numbers;
use App\Models\Product\Product;

/**
 * Class Item
 * @package App\Models\Item
 *
 * @property int          orderId
 * @property string       type
 * @property int          productId
 * @property string       name
 * @property int          quantity
 * @property int          unitPrice
 * @property string       total
 * @property int          isBundle
 * @property string       purchasedSection
 *
 * @property Order        order
 * @property Product|Gift detail
 * @property Product      product
 * @property Gift         gift
 */
class Item extends AbstractModel
{
    use Numbers;

    const TYPE_PRODUCT = 'product';
    const TYPE_GIFT    = 'gift';

    public static $makePricesInteger = false;

    /**
     * @var string
     */
    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId',
        'type',
        'productId',
        'name',
        'quantity',
        'unitPrice',
        'total',
        'isBundle',
        'purchasedSection'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'orderId'   => 'integer',
        'productId' => 'integer',
        'quantity'  => 'integer',
        'unitPrice' => 'integer',
        'total'     => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function detail()
    {
        return $this->morphTo('detail', 'type', 'productId', 'id');
    }

    /**
     * @param $unitPrice
     *
     * @return float
     */
    public function getUnitPriceAttribute($unitPrice): float
    {
        return $this->toFloatAmount($unitPrice);
    }

    /**
     * @param $unitPrice
     */
    public function setUnitPriceAttribute($unitPrice)
    {
        $this->attributes['unitPrice'] = static::$makePricesInteger ? $this->toIntAmount($unitPrice) : $unitPrice;
    }

    /**
     * @param $total
     *
     * @return float
     */
    public function getTotalAttribute($total): float
    {
        return $this->toFloatAmount($total);
    }

    /**
     * @param $total
     */
    public function setTotalAttribute($total)
    {
        $this->attributes['total'] = static::$makePricesInteger ? $this->toIntAmount($total) : $total;
    }

    public function isProduct()
    {
        return ($this->type === self::TYPE_PRODUCT);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'product':
                return $this->product();
            case 'gift':
                return $this->gift();
        }

        return parent::__get($key);
    }

    /**
     * @return Product|null
     */
    private function product()
    {
        return $this->isProduct() ? $this->detail : null;
    }

    /**
     * @return Gift|null
     */
    private function gift()
    {
        return !$this->isProduct() ? $this->detail : null;
    }
}