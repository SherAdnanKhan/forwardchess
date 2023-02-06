<?php

namespace App\Models\Blog;

use App\Models\AbstractModel;
use App\Models\Numbers;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Article
 * @package App\Models\Article
 *
 * @property string  title
 * @property string  content
 * @property string  url
 * @property boolean active
 * @property string  publishDate
 *
 * @property tag[]   tags
 */
class Article extends AbstractModel
{
    use SoftDeletes, Numbers;

    /**
     * @var string
     */
    protected $table = 'blog_articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'publishDate',
        'active',
        'url',
        'created_at'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this
            ->belongsToMany(
                Tag::class,
                'blog_articles_tags',
                'articleId',
                'tagId'
            )
            ->withTimestamps();
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
     * @param string $format
     *
     * @return string
     */
    public function getPublishDateFormatted(string $format = 'M d, Y')
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
        $result = $this
            ->query()
            ->where('url', $url)
            ->first();

        return empty($result) ? null : $result->id;
    }
}
