<?php

namespace App\Models\Order;

use App\Models\AbstractModel;
use App\Models\Numbers;
use App\Models\TaxRate;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Models\Order
 *
 * @property int       userId
 * @property int       status
 * @property string    paymentMethod
 * @property string    currency
 * @property float     subTotal
 * @property string    coupon
 * @property float     discount
 * @property string    taxRateCountry
 * @property float     taxRateAmount
 * @property float     taxAmount
 * @property float     total
 * @property string    refNo
 * @property boolean   allowDownload
 * @property string    ipAddress
 * @property string    userAgent
 * @property string    completedDate
 * @property string    paidDate
 *
 * @property string    email
 * @property string    fullName
 *
 * @property User      user
 * @property Billing   billing
 * @property Payment   payment
 * @property Item[]    items
 * @property Item[]    blockItems
 * @property Voucher[] vouchers
 * @property TaxRate   taxRate
 */
class Order extends AbstractModel
{
    use SoftDeletes, Numbers;

    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_REFUNDED   = 'refunded';
    const STATUS_ON_HOLD    = 'on-hold';

    public static $makePricesInteger = false;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId',
        'status',
        'paymentMethod',
        'currency',
        'subTotal',
        'coupon',
        'discount',
        'taxRateCountry',
        'taxRateAmount',
        'taxAmount',
        'total',
        'allowDownload',
        'ipAddress',
        'completedDate',
        'paidDate',
        'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'userId'        => 'integer',
        'subTotal'      => 'integer',
        'discount'      => 'integer',
        'taxRateAmount' => 'float',
        'taxAmount'     => 'integer',
        'total'         => 'integer',
        'allowDownload' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'completedDate',
        'paidDate',
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
    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class, 'taxRateCountry', 'code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billing()
    {
        return $this->hasOne(Billing::class, 'orderId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'orderId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'orderId')->where('isBundle', 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blockItems()
    {
        return $this->hasMany(Item::class, 'orderId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'orderId');
    }

    /**
     * @param $subTotal
     *
     * @return float
     */
    public function getSubTotalAttribute($subTotal): float
    {
        return $this->toFloatAmount($subTotal);
    }

    /**
     * @param $subTotal
     */
    public function setSubTotalAttribute($subTotal)
    {
        $this->attributes['subTotal'] = static::$makePricesInteger ? $this->toIntAmount($subTotal) : $subTotal;
    }

    /**
     * @param $discount
     *
     * @return float
     */
    public function getDiscountAttribute($discount): float
    {
        return $this->toFloatAmount($discount);
    }

    /**
     * @param $discount
     */
    public function setDiscountAttribute($discount)
    {
        $this->attributes['discount'] = static::$makePricesInteger ? $this->toIntAmount($discount) : $discount;
    }

    /**
     * @param $taxAmount
     *
     * @return float
     */
    public function getTaxAmountAttribute($taxAmount): float
    {
        return $this->toFloatAmount($taxAmount);
    }

    /**
     * @param $taxAmount
     */
    public function setTaxAmountAttribute($taxAmount)
    {
        $this->attributes['taxAmount'] = static::$makePricesInteger ? $this->toIntAmount($taxAmount) : $taxAmount;
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

    /**
     * @param string $format
     *
     * @return string
     */
    public function getCompletedDateFormatted(string $format = 'd/m/Y H:i')
    {
        return $this->formatDate($this->completedDate, $format);
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getPaidDateFormatted(string $format = 'd/m/Y H:i')
    {
        return $this->formatDate($this->paidDate, $format);
    }

    /**
     * Save the model to the database.
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $query = $this->newQueryWithoutScopes();

        // If the "saving" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->isDirty() ?
                $this->performUpdate($query) : false;
        }

        // If the model is brand new, we'll insert it into our database and set the
        // ID attribute on the model to the value of the newly inserted row's ID
        // which is typically an auto-increment value managed by the database.
        else {
            if (empty($this->attributes['refNo'])) {
                $this->setAttribute('refNo', $this->generateReNo());
            }

            $saved = $this->performInsert($query);
        }

        if ($saved) {
            $this->finishSave($options);
        }

        return $saved;
    }

    /**
     * @return string
     */
    public function generateReNo(): string
    {
        return strtoupper(uniqid());
    }

    /**
     * @param string $refNo
     *
     * @return null|string
     */
    public function getIdFromRefNo(string $refNo): ?string
    {
        $result = $this->query()
            ->where('refNo', $refNo)
            ->first();

        return empty($result) ? null : $result->id;
    }

    /**
     * @return int
     */
    public function getTotalWithoutTaxes(): int
    {
        return $this->attributes['subTotal'] - $this->attributes['discount'];
    }
}