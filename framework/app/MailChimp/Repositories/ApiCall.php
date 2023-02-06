<?php

namespace App\MailChimp\Repositories;

use App\MailChimp\Assets\ApiResponse;
use GuzzleHttp\Exception\ClientException;

/**
 * Trait ApiCall
 * @package App\MailChimp\Repositories
 */
trait ApiCall
{
    /**
     * @param \Closure $caller
     *
     * @return ApiResponse
     */
    protected function makeCall(\Closure $caller): ApiResponse
    {
        try {
            $response = $caller();

            return ApiResponse::make($response);
        } catch (ClientException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->getContents());

            return ApiResponse::make($response, false);
        }
    }
}