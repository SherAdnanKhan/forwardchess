<?php

namespace App\Http\Resources\Traits;

use App\Models\Blog\Article;

/**
 * Trait ArticleTransformer
 * @package App\Http\Resources\Traits
 */
trait ArticleTransformer
{
    /**
     * @param Article $article
     * @param bool    $fullMode
     *
     * @return array
     */
    protected function getPage(Article $article, bool $fullMode = false): array
    {
        $data = [
            'id'          => $article->id,
            'title'       => $article->title,
            'publishDate' => $article->publishDate,
            'active'      => $article->active,
        ];

        if ($fullMode) {
            $tags = $article->tags->map(function ($tag) {
                return $tag->id;
            })->unique();

            $data = array_merge($data, [
                'tags'    => $tags,
                'content' => $article->content,
                'url'     => route('site.articles.show', $article->url)
            ]);
        }

        $data = array_merge($data, [
            'created_at' => $article->getCreatedAtFormatted(),
            'updated_at' => $article->getUpdatedAtFormatted(),
            'deleted_at' => $article->getDeletedAtFormatted(),
        ]);

        return $data;
    }
}