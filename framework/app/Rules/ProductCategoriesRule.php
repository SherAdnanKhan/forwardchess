<?php

namespace App\Rules;

use App\Repositories\Product\CategoryRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

/**
 * Class ProductCategoriesRule
 * @package App\Rules
 */
class ProductCategoriesRule implements Rule
{
    /**
     * @var Collection
     */
    private $categories;

    /**
     * ProductCategoriesRule constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categories = $categoryRepository->get()->map(function ($category) {
            return $category->id;
        })->flip();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $category) {
            if (!$this->categories->has($category)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid categories list.';
    }
}
