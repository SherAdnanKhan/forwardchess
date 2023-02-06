<?php

namespace App\Assets\MailChimp;

use App\Models\Cart\Cart;
use App\Models\User\User;

/**
 * Class CartAction
 * @package App\Assets
 */
class CartAction
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var Cart
     */
    private Cart $cart;

    /**
     * @var int
     */
    private int $itemId;

    /**
     * @param string    $name
     * @param User|null $user
     * @param Cart|null $cart
     * @param int|null  $itemId
     *
     * @return CartAction
     */
    public static function make(string $name, User $user = null, Cart $cart = null, int $itemId = null): CartAction
    {
        $instance = new static($name);
        if (!empty($user)) {
            $instance->setUser($user);
        }

        if (!empty($cart)) {
            $instance->setCart($cart);
        }

        if (!empty($itemId)) {
            $instance->setItemId($itemId);
        }

        return $instance;
    }

    /**
     * CartAction constructor.
     *
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isReady(): bool
    {
        $config = config('mailchimp');
        if (!isProduction() || empty($config['apiKey']) || empty($this->user) || !$this->user->subscribed || empty($this->cart) || empty($this->itemId)) {
            return false;
        }

        if (!empty($config['enabledEmails']) && !in_array($this->user->email, $config['enabledEmails'])) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return CartAction
     */
    public function setUser(User $user): CartAction
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Cart
     */
    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     *
     * @return CartAction
     */
    public function setCart(Cart $cart): CartAction
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    /**
     * @param int $itemId
     *
     * @return CartAction
     */
    public function setItemId(int $itemId): CartAction
    {
        $this->itemId = $itemId;

        return $this;
    }
}