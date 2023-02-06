<?php

namespace App\Models\Order;

use App\Models\AbstractModel;
use App\Models\Gift;
use App\Models\Numbers;

/**
 * Class Item
 * @package App\Models\Item
 *
 * @property int     orderId
 * @property int     giftId
 * @property int     amount
 * @property boolean refunded
 *
 * @property Order   order
 * @property Gift    gift
 */
class Voucher extends AbstractModel
{
    use Numbers;

    public static $makePricesInteger = false;

    /**
     * @var string
     */
    protected $table = 'order_vouchers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId',
        'giftId',
        'amount',
        'refunded',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'orderId'  => 'integer',
        'giftId'   => 'integer',
        'amount'   => 'integer',
        'refunded' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gift()
    {
        return $this->belongsTo(Gift::class, 'giftId', 'id');
    }

    /**
     * @param $unitPrice
     *
     * @return float
     */
    public function getAmountAttribute($unitPrice): float
    {
        return $this->toFloatAmount($unitPrice);
    }

    /**
     * @param $amount
     */
    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = static::$makePricesInteger ? $this->toIntAmount($amount) : $amount;
    }
}