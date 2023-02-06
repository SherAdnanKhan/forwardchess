<?php

namespace App\Models\Cart;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Billing
 * @package App\Models\Cart
 */
class Billing implements Arrayable
{
    /**
     * @var string|null
     */
    private ?string $firstName = null;

    /**
     * @var string|null
     */
    private ?string $lastName = null;

    /**
     * @var string|null
     */
    private ?string $email = null;

    /**
     * @param array $data
     *
     * @return Billing
     */
    public static function make(array $data = []): Billing
    {
        return (new static)->setBulkData($data);
    }

    /**
     * @param array $data
     *
     * @return Billing
     */
    public function setBulkData(array $data = []): Billing
    {
        foreach ($data as $property => $value) {
            $methodName = 'set' . ucfirst($property);
            if (method_exists($this, $methodName)) {
                call_user_func_array([$this, $methodName], [$value]);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return Billing
     */
    public function setFirstName(string $firstName = null): Billing
    {
        if (!empty($firstName)) {
            $this->firstName = ucwords($firstName);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return Billing
     */
    public function setLastName(string $lastName = null): Billing
    {
        if (!empty($lastName)) {
            $this->lastName = ucwords($lastName);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return Billing
     */
    public function setEmail(string $email = null): Billing
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
            'email'     => $this->email
        ];
    }
}