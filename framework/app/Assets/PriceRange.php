<?php

namespace App\Assets;

/**
 * Class PriceRange
 * @package App\Assets
 */
class PriceRange
{
    /**
     * @var int
     */
    private $minPrice = 0;

    /**
     * @var int
     */
    private $maxPrice = 0;

    /**
     * @param $minPrice
     * @param $maxPrice
     *
     * @return PriceRange
     */
    public static function make($minPrice, $maxPrice): PriceRange
    {
        $instance = new static;
        $instance->setMinPrice($minPrice);
        $instance->setMaxPrice($maxPrice);

        return $instance;
    }

    /**
     * @return int
     */
    public function getMinPrice(): int
    {
        return $this->minPrice * 100;
    }

    /**
     * @param int $minPrice
     *
     * @return PriceRange
     */
    public function setMinPrice(int $minPrice): PriceRange
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPrice(): int
    {
        return $this->maxPrice * 100;
    }

    /**
     * @param int $maxPrice
     *
     * @return PriceRange
     */
    public function setMaxPrice(int $maxPrice): PriceRange
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }
}