<?php

namespace App\Assets;

use App\Models\Cart\Cart;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;


/**
 * Class ControllerSiteData
 * @package App\Assets
 */
class ControllerSiteData implements Arrayable
{
    /**
     * @var Collection
     */
    public $publishers = [];

    /**
     * @var Collection
     */
    public $categories = [];

    /**
     * @var Collection
     */
    public $products = [];

    /**
     * @var Cart
     */
    public $cart = [];

    /**
     * @var Collection
     */
    public $wishlist;

    /**
     * @param array $data
     *
     * @return ControllerSiteData
     */
    public static function make(array $data): ControllerSiteData
    {
        $instance = new static;
        foreach ($data as $property => $value) {
            if (!property_exists($instance, $property)) {
                continue;
            }

            $instance->{$property} = $value;
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'publishers' => $this->publishers,
            'categories' => $this->categories,
            'products'   => $this->products,
            'cart'       => $this->cart,
            'wishlist'   => $this->wishlist->toArray()
        ];
    }
}