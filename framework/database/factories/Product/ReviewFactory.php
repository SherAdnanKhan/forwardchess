<?php

namespace Database\Factories\Product;

use App\Models\Product\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userId'      => 10280,
            'productId'   => 366,
            'description' => $this->faker->sentence(),
            'title'       => $this->faker->words(3, true),
            'rating'      => $this->faker->numberBetween(0, 5),
            'approved'    => true,
        ];
    }
}

