<?php

namespace App\Repositories\Blog;

use App\Repositories\AbstractModelRepository;
use Illuminate\Support\Collection;

/**
 * Class TagRepository
 * @package App\Repositories\Article
 */
class TagRepository extends AbstractModelRepository
{
    /**
     * @return Collection
     */
    public function getActiveTags(): Collection
    {
        $result = $this
            ->getResource()
            ->query()
            ->select('tags.id')
            ->join('pages_tags', 'pages_tags.tagId', '=', 'tags.id')
            ->join('pages', 'pages.id', '=', 'pages_tags.pageId')
            ->where('pages.active', '=', 1)
            ->groupBy('tags.id')
            ->get();

        $ids = [];
        foreach ($result as $item) {
            $ids[] = $item->id;
        }

        return $this
            ->getResource()
            ->query()
            ->whereIn('id', $ids)
            ->get();
    }
}
