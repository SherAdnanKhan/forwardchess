<?php

namespace App\Models\Product;

use App\Models\AbstractModel;
use Illuminate\Support\Facades\Storage;

/**
 * Class Publisher
 * @package App\Models\Product
 *
 * @property string    name
 * @property string    logo
 * @property string    logoUrl
 * @property int       position
 *
 * @property Product[] products
 */
class Publisher extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'publishers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'position'
    ];

    /**
     * @var array
     */
    protected $appends = ['logoUrl'];

    /**
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        return empty($this->logo) ? '' : Storage::disk('public')->url('publishers/' . $this->logo);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'publisherId');
    }
}