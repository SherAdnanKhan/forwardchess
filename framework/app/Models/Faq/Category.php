<?php

namespace App\Models\Faq;

use App\Models\AbstractModel;

/**
 * Class Category
 * @package App\Models
 *
 * @property string name
 * @property Faq[] posts
 */
class Category extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'faq_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faq()
    {
        return $this->hasMany(Faq::class, 'categoryId');
    }
}