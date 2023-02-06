<?php

namespace App\Events;

use App\Models\Product\Product;
use App\Models\User\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ProductVisitedEvent
 * @package App\Events
 */
class ProductVisitedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var Product
     */
    private Product $product;

    /**
     * @var User|null
     */
    private ?User $user;

    /**
     * ProductVisitedEvent constructor.
     *
     * @param Product   $product
     * @param User|null $user
     */
    public function __construct(Product $product, User $user = null)
    {
        $this->product = $product;
        $this->user    = $user;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
