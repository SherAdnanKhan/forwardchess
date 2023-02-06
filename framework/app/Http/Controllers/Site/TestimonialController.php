<?php

namespace App\Http\Controllers\Site;

use App\Assets\SortBy;
use App\Services\TestimonialService;
use Illuminate\Support\Collection;

/**
 * Class TestimonialController
 * @package App\Http\Controllers\Site
 */
class TestimonialController extends AbstractSiteController
{
    /**
     * @param TestimonialService $testimonialService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TestimonialService $testimonialService)
    {
        /** @var Collection $categories */
        $testimonials = $testimonialService->all(['active' => 1], SortBy::make('created_at', 'desc'));
        if (!$testimonials->isEmpty()) {
            $this->addViewData([
                'testimonials' => $testimonials
            ]);
        }

        return view('site.pages.testimonials', $this->viewData);
    }
}