<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Models\User\User;

/**
 * Class Gift
 * @package App\Models
 *
 * @property int     userId
 * @property string  code
 * @property int     amount
 * @property int     originalAmount
 * @property string  friendEmail
 * @property string  friendName
 * @property string  friendMessage
 * @property int     orderId
 * @property boolean enabled
 * @property string  expireDate
 *
 * @property string  buyer
 * @property User[]  user
 * @property Order[] order
 */
class Gift extends AbstractModel
{
    use Numbers;

    /**
     * @var string
     */
    protected $table = 'gifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId',
        'code',
        'amount',
        'minAmount',
        'originalAmount',
        'friendEmail',
        'friendName',
        'friendMessage',
        'orderId',
        'enabled',
        'expireDate',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'userId'         => 'integer',
        'amount'         => 'integer',
        'originalAmount' => 'integer',
        'orderId'        => 'integer',
        'enabled'        => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expireDate',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'id');
    }

    /**
     * @param         $amount
     * @param boolean $convert
     */
    public function setAmountAttribute($amount, $convert = true)
    {
        $this->attributes['amount'] = $convert ? $this->toIntAmount($amount) : $amount;
    }

    /**
     * @param $amount
     */
    public function setOriginalAmountAttribute($amount)
    {
        $this->attributes['originalAmount'] = $this->toIntAmount($amount);
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getExpireDateFormatted(string $format = 'Y-m-d H:i')
    {
        return $this->formatDate($this->expireDate, $format);
    }
}