<?php

namespace App\Http\Controllers\Site;

use App\Assets\SortBy;
use App\Services\FaqService;
use Illuminate\Support\Collection;

/**
 * Class FaqController
 * @package App\Http\Controllers\Site
 */
class FaqController extends AbstractSiteController
{
    /**
     * @param FaqService $faqService
     * @param string     $categoryName
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FaqService $faqService, string $categoryName = null)
    {
        /** @var Collection $categories */
        $categories = $faqService->getCategories();
        if (!$categories->isEmpty()) {
            $categoryId = $this->getCategoryId($categories, $categoryName);

            $posts = $faqService->all([
                'categoryId' => $categoryId,
                'active'     => 1
            ], SortBy::make('position', 'asc'));

            $this->addViewData([
                'faqCategories' => $categories,
                'faqCategoryId' => $categoryId,
                'faqPosts'      => $posts
            ]);
        }

        return view('site.pages.faq', $this->viewData);
    }

    /**
     * @param Collection  $categories
     * @param string|null $categoryName
     *
     * @return int
     */
    private function getCategoryId($categories, $categoryName = null): int
    {
        $category = null;
        if (!empty($categoryName)) {
            $category = $categories->first(function ($category) use ($categoryName) {
                return (kebab_case($category->name) === kebab_case($categoryName));
            });
        }

        if (empty($category)) {
            $category = $categories->first();
        }

        return $category->id;
    }
}