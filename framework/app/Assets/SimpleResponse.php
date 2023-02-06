<?php

namespace App\Assets;

/**
 * Class SimpleResponse
 * @package App\Assets
 */
class SimpleResponse
{
    /**
     * @var string
     */
    private $message = '';

    /**
     * @var bool
     */
    private $success = false;

    /**
     * @var null
     */
    private $value = null;

    /**
     * @param string $message
     * @param bool   $success
     * @param mixed  $value
     *
     * @return SimpleResponse
     */
    public static function make(string $message = '', bool $success = false, $value = null): SimpleResponse
    {
        $instance = new static;
        $instance->setMessage($message);
        $instance->setSuccess($success);
        $instance->setValue($value);

        return $instance;
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
     * @return SimpleResponse
     */
    public function setMessage(string $message): SimpleResponse
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return SimpleResponse
     */
    public function setSuccess(bool $success): SimpleResponse
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     *
     * @return SimpleResponse
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}