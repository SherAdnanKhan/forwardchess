<?php
/**
 * Created by PhpStorm.
 * User: radudalbea
 * Date: 9/17/18
 * Time: 10:07 PM
 */

namespace App\Assets;


class MobileRequest
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;

    /**
     * @var mixed
     */
    private $params;

    /**
     * @param $method
     * @param $url
     * @param $params
     *
     * @return MobileRequest
     */
    public static function make($method, $url, $params = null): MobileRequest
    {
        return (new static)
            ->setMethod($method)
            ->setUrl($url)
            ->setParams($params);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return strtoupper($this->method);
    }

    /**
     * @param string $method
     *
     * @return MobileRequest
     */
    public function setMethod(string $method): MobileRequest
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return MobileRequest
     */
    public function setUrl(string $url): MobileRequest
    {
        $this->url = config('gcp.url') . trim($url, '/') . '/';

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     *
     * @return MobileRequest
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}
