<?php

namespace App\Http\Controllers\Site;

use App\Services\Blog\ArticleService;
use App\Services\Blog\TagService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class BlogController
 * @package App\Http\Controllers\Site
 */
class BlogController extends AbstractSiteController
{
    /**
     * @param TagService     $tagService
     * @param ArticleService $articleService
     * @param string|null    $tagName
     *
     * @return Application|Factory|View
     */
    public function index(TagService $tagService, ArticleService $articleService, string $tagName = null)
    {
        $tags     = $tagService->getActiveTags();
        $tagId    = $tagService->getTagIdByName($tagName);
        $articles = $articleService->getArticlesByTag($tagId);

        $this->addViewData([
            'tags'     => $tags,
            'tagId'    => $tagId,
            'articles' => $articles
        ]);

        return view('site.pages.articles', $this->viewData);
    }

    /**
     * @param ArticleService $service
     * @param string         $url
     */
    public function show(ArticleService $service, $url)
    {
        $this->addViewData([
            'article' => $service->getArticleByUrl($url)
        ]);

        return view('site.pages.article', $this->viewData);
    }
}
