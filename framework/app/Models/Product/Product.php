<?php

namespace App\Models\Product;

use App\Models\AbstractModel;
use App\Models\Numbers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class Product
 * @package App\Models\Product
 *
 * @property int        publisherId
 * @property string     title
 * @property string     description
 * @property string     author
 * @property string     sku
 * @property string     url
 * @property string     image
 * @property integer    price
 * @property string     discountType
 * @property integer    discount
 * @property string     discountStartDate
 * @property string     discountEndDate
 * @property string     nrPages
 * @property string     totalSales
 * @property string     publishDate
 * @property int        position
 * @property int        active
 * @property string     imageUrl
 * @property string     level
 * @property int        rating
 * @property float      sellPrice
 * @property boolean    isComingSoon
 * @property int        isBundle
 *
 * @property string     publisherName
 * @property Publisher  publisher
 * @property Category[] categories
 * @property Collection parents
 * @property Collection children
 */
class Product extends AbstractModel
{
    use SoftDeletes, Numbers;

    const TYPE_PERCENT = 'percent';
    const TYPE_AMOUNT  = 'amount';

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'publisherId',
        'title',
        'description',
        'author',
        'sku',
        'url',
        'image',
        'price',
        'level',
        'rating',
        'discountType',
        'discount',
        'discountStartDate',
        'discountEndDate',
        'nrPages',
        'totalSales',
        'publishDate',
        'position',
        'active',
        'isBundle',
        'created_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'publisherId'  => 'integer',
        'price'        => 'integer',
        'discount'     => 'integer',
        'totalSales'   => 'integer',
        'rating'       => 'integer',
        'active'       => 'boolean',
        'isBundle'     => 'boolean',
        'isComingSoon' => 'boolean',
    ];

    /**
     * Array of our custom model events declared under model property $observables
     * @var array
     */

    protected $observables = [
        'changed',
    ];

    /**
     * @var array
     */
    protected $appends = ['imageUrl', 'sellPrice'];

    /**
     * @return BelongsTo
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisherId', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Category::class,
                'products_categories',
                'productId',
                'categoryId'
            )
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function parents(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'product_bundles',
                'productId',
                'bundleId'
            )
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function children(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'product_bundles',
                'bundleId',
                'productId'
            )
            ->withTimestamps();
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::disk('public')->url('products/' . (empty($this->image) ? 'default-image.jpg' : $this->image));
    }

    /**
     * @param $title
     */
    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['url']   = kebab_case($title);
    }

    /**
     * @return int
     */
    public function getSellPriceAttribute(): int
    {
        if (!$this->hasDiscount()) {
            return $this->price;
        }

        if ($this->isBundle) {
            $bundlePrice = 0;
            foreach ($this->children as $key => $value) {
                $bundlePrice += (float)bcdiv(($this->toFloatAmount($value->sellPrice) - $this->toFloatAmount($value->sellPrice) * $this->toFloatAmount($this->discount) / 100), 1, 2);

            }
            $bundlePrice = $this->toIntAmount($bundlePrice);
            return ($this->discountType === self::TYPE_PERCENT)
                ? (int)($bundlePrice)
                : $this->discount;
        }

        return ($this->discountType === self::TYPE_PERCENT)
            ? (int)($this->price - $this->price * $this->discount / 10000)
            : $this->discount;
    }

    /**
     * @param $price
     */
    public function setPriceAttribute($price)
    {
        $this->attributes['price'] = $this->toIntAmount($price);
    }

    /**
     * @param $discount
     */
    public function setDiscountAttribute($discount)
    {
        $this->attributes['discount'] = $this->toIntAmount($discount);
    }

    /**
     * @return int
     */
    public function denyBuy(): int
    {
        return (!$this->active || $this->isComingSoon) ? 1 : 0;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getPublishDateFormatted(string $format = 'M d, Y'): string
    {
        return $this->formatDate($this->publishDate, $format, 'Y-m-d');
    }

    /**
     * @param string $url
     *
     * @return null|string
     */
    public function getIdFromUrl(string $url): ?string
    {
        $result = $this->query()
            ->where('url', $url)
            ->first();

        return empty($result) ? null : $result->id;
    }

    /**
     * @return float
     */
    public function getRatingAttribute(): float
    {
        $rating  = $this->toFloatAmount($this->attributes['rating']);
        $decimal = $rating - floor($rating);

        if ($decimal <= 0.25) {
            $rating = floor($rating);
        } elseif ($decimal > 0.25 && $decimal < 0.75) {
            $rating = floor($rating) + 0.5;
        } elseif ($decimal >= 0.75) {
            $rating = ceil($rating);
        }

        return $rating;
    }

    /**
     * @return bool
     */
    public function hasDiscount(): bool
    {
        $now = Carbon::now();

        return (
            !empty($this->discountStartDate) &&
            !empty($this->discountEndDate) &&
            !empty($this->discount) &&
            ($this->discount > 0) &&
            (Carbon::createFromFormat('Y-m-d H:i:s', $this->discountStartDate . ' 00:00:00') <= $now) &&
            (Carbon::createFromFormat('Y-m-d H:i:s', $this->discountEndDate . ' 23:59:59') >= $now)
        );
    }

    public function calculateBundlePrice()
    {
        $price = $this->children->sum(function (Product $child) {
            return $child->sellPrice;
        });

        /*
        // Fo the bundle product price as per discount
        $price = $this->children->sum(function (Product $child) {
            if ($this->isBundle) {
                return $this->toIntAmount(bcdiv(($this->toFloatAmount($child->sellPrice) - $this->toFloatAmount($child->sellPrice) * $this->toFloatAmount($this->discount) / 100) , 1, 2));
            }
            return $child->sellPrice;
        });
        */

        $this->price = $price / 100;
    }
}