<?php

namespace App\Assets;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class State
 * @package App\Assets
 *
 * @property string code
 * @property string name
 */
class State implements Arrayable
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * State constructor.
     *
     * @param string $code
     * @param string $name
     */
    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return string
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'code':
                return $this->getCode();
            case 'name':
                return $this->getName();
            default:
                throw new \Exception("Unknown property `{$name}` for Country");
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}