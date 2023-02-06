<?php

namespace App\Exceptions;

/**
 * Class InvalidGiftException
 * @package App\Common
 */
class InvalidGiftException extends \Exception
{
    /**
     * Create a new exception.
     *
     * @param string $message
     */
    public function __construct(string $message = 'Invalid gift card code.')
    {
        parent::__construct($message);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $this->getMessage()], 400)
            : redirect()->guest(route('login'));
    }
}