<?php

namespace App\Models\Faq;

use App\Models\AbstractModel;

/**
 * Class Faq
 * @package App\Models
 *
 * @property int    categoryId
 * @property string categoryName
 * @property string question
 * @property string answer
 * @property int    position
 * @property bool   active
 */
class Faq extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'faq_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'categoryId',
        'question',
        'answer',
        'position',
        'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }
}