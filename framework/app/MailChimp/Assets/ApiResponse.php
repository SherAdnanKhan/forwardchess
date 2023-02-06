<?php

namespace App\MailChimp\Assets;

/**
 * Class ApiResponse
 * @package App\MailChimp\Assets
 */
class ApiResponse
{
    /**
     * @var mixed
     */
    private $response;

    /**
     * @var bool
     */
    private $success;

    public static function make($response, $success = true)
    {
        return new static($response, $success);
    }

    /**
     * ApiResponse constructor.
     *
     * @param      $response
     * @param bool $success
     */
    private function __construct($response, bool $success)
    {
        $this->response = $response;
        $this->success  = $success;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return mixed|null
     */
    public function getErrorMessage()
    {
        return $this->isSuccess() ? null : $this->getResponse()->title;
    }

    /**
     * @return mixed|null
     */
    public function getErrorStatus()
    {
        return $this->isSuccess() ? null : $this->getResponse()->status;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if (!$this->isSuccess()) {
            throw new \Exception($this->getErrorMessage(), $this->getErrorStatus());
        }
    }
}
