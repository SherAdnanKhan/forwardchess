<?php

namespace App\Common\Helpers;

use App\Common\Exception;

/**
 * Trait ResponseHelper
 * @package App\Common\Helpers
 */
trait ResponseHelper
{
    /**
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    protected function errorBadRequest()
    {
        $response = ['success' => false, 'message' => 'bad request'];

        if ($this->isInternalRequest()) {
            throw new Exception($response['message']);
        }

        return response()->json($response, 400);
    }

    /**
     * @return \Illuminate\Http\Response|array
     */
    protected function noContent()
    {
        $response = [];

        return $this->isInternalRequest() ? $response : response()->json($response, 200);
    }
}