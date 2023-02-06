<?php

namespace App\MailChimp\Assets;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Store
 * @package App\MailChimp\Assets
 */
class Store implements Arrayable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $listId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $currency = 'USD';

    /**
     * @param string $id
     *
     * @return Store
     */
    public function setId(string $id): Store
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $listId
     *
     * @return Store
     */
    public function setListId(string $listId): Store
    {
        $this->listId = $listId;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return Store
     */
    public function setName(string $name): Store
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $currency
     *
     * @return Store
     */
    public function setCurrency(string $currency): Store
    {
        $this->currency = $currency;
        return $this;
    }

    public function toArray()
    {
        return [
            'id'            => $this->id,
            'list_id'       => $this->listId,
            'name'          => $this->name,
            'currency_code' => $this->currency,
        ];
    }
}