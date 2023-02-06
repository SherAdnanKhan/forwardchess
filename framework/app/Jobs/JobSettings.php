<?php

namespace App\Jobs;

/**
 * Class AbstractMobileJob
 * @package App\Jobs\Mobile
 */
trait JobSettings
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @param \Exception $e
     *
     * @throws \Exception
     */
    protected function onFail(\Exception $e)
    {
        if ($this->attempts() < $this->tries) {
            $delayInSeconds = 5;
            $this->release($delayInSeconds);
        } else {
            throw $e;
        }
    }
}
