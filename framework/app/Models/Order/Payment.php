<?php

namespace App\Models\Order;

use App\Models\AbstractModel;

/**
 * Class Payment
 * @package App\Models\Payment
 *
 * @property int    orderId
 * @property string firstName
 * @property string lastName
 * @property string email
 * @property string payerId
 * @property string countryCode
 * @property string feeAmount
 * @property string feeCurrency
 * @property string transactionKey
 * @property string data
 */
class Payment extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'order_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId',
        'firstName',
        'lastName',
        'email',
        'payerId',
        'countryCode',
        'feeAmount',
        'feeCurrency',
        'transactionKey',
        'data',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'id');
    }
}