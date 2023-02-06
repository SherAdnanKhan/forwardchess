<?php

namespace App\Rules;

use App\Repositories\Product\ProductRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

/**
 * Class CouponProductsRule
 * @package App\Rules
 */
class CouponProductsRule implements Rule
{
    /**
     * @var Collection
     */
    private $products;

    /**
     * ProductCategoriesRule constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->products = $productRepository->get()->map(function ($product) {
            return $product->id;
        })->flip();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $product) {
            if (!$this->products->has($product)) {
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
        return 'Invalid products list.';
    }
}
