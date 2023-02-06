<?php

namespace App\Repositories\Blog;

use App\Assets\SortBy;
use App\Builders\ArticleBuilder;
use App\Models\Blog\Article;
use App\Repositories\AbstractModelRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ArticleRepository
 * @package App\Repositories\Blog
 */
class ArticleRepository extends AbstractModelRepository
{
    /**
     * @param array       $search
     * @param SortBy|null $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $search['trashIncluded'] = isset($search['trashIncluded']) ? (bool)$search['trashIncluded'] : $this->isTrashedIncludedInSearch();
        $search['sortBy']        = $sortBy;
        return ArticleBuilder::make($search)->init();
    }

    /**
     * @param Article $article
     * @param array   $tags
     *
     * @return ArticleRepository
     */
    public function saveTags(Article $article, array $tags): self
    {
        $query = 'DELETE FROM `blog_articles_tags` WHERE `articleId` = ?';
        DB::delete($query, [$article->id]);

        $query = 'INSERT INTO `blog_articles_tags`(`articleId`, `tagId`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), NOW())';

        foreach ($tags as $tag) {
            DB::insert($query, [$article->id, $tag]);
        }

        $article->load('tags');
        $article->fireEvent('updating');

        return $this;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function getIdFromUrl(string $url): ?string
    {
        /** @var Article $page */
        $page = $this->getResource();

        return $page->getIdFromUrl($url);
    }
}