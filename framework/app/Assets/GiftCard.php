<?php

namespace App\Assets;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class GiftCard
 * @package App\Assets
 */
class GiftCard implements Arrayable
{
    /**
     * @var float
     */
    private $amount = 20;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @param array $data
     *
     * @return GiftCard
     */
    public static function make(array $data = []): GiftCard
    {
        /** @var GiftCard $instance */
        $instance = app(GiftCard::class);

        $mapper = [
            'amount'  => 'setAmount',
            'name'    => 'setName',
            'email'   => 'setEmail',
            'message' => 'setMessage',
        ];

        foreach ($data as $property => $value) {
            if (is_null($value) || !isset($mapper[$property])) {
                continue;
            }

            call_user_func_array([$instance, $mapper[$property]], [$value]);
        }

        return $instance;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return GiftCard
     */
    public function setName(string $name): GiftCard
    {
        $this->name = ucwords($name);

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     *
     * @return GiftCard
     */
    public function setAmount(string $amount): GiftCard
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return GiftCard
     */
    public function setEmail(string $email): GiftCard
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return GiftCard
     */
    public function setMessage(string $message): GiftCard
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'amount'  => $this->amount,
            'name'    => $this->name,
            'email'   => $this->email,
            'message' => $this->message,
        ];
    }
}