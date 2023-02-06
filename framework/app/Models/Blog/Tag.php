<?php

namespace App\Models\Blog;

use App\Models\AbstractModel;

/**
 * Class Tag
 * @package App\Models\Blog
 *
 * @property string    name
 * @property Article[] articles
 */
class Tag extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'blog_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this
            ->belongsToMany(
                Article::class,
                'blog_articles_tags',
                'tagId',
                'articleId'
            )
            ->withTimestamps();
    }
}