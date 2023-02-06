<?php

namespace App\Models\Product;

use App\Models\AbstractModel;

/**
 * Class Category
 * @package App\Models\Product
 *
 * @property string    name
 * @property int       position
 * @property string    url
 * @property Product[] products
 */
class Category extends AbstractModel
{
    const COMING_SOON = 8;
    const BUNDLES     = 12;

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
        'url',
    ];

    /**
     * @param $name
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $url                      = preg_replace("/[^A-Za-z0-9 ]/", '', $name);
        $this->attributes['url']  = kebab_case($url);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this
            ->belongsToMany(
                Product::class,
                'products_categories',
                'categoryId',
                'productId'
            )
            ->withTimestamps();
    }

    /**
     * @return static
     */
    public static function getBundlesCategory(): self
    {
        $instance = new static;
        return $instance::where('url', 'bundles')->first();
    }
}
