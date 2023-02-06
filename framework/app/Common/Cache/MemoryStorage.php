<?php

namespace App\Common\Cache;

use Carbon\Carbon;
use Illuminate\Support\InteractsWithTime;

trait MemoryStorage
{
    use InteractsWithTime;

    /**
     * @var array
     */
    static $memoryValues = [];

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getMemoryValue(string $key)
    {
        $value = null;
        if (array_key_exists($key, self::$memoryValues) && (self::$memoryValues[$key]->timestamp > time())) {
            if (is_object(self::$memoryValues[$key]->value)) {
                $value = clone self::$memoryValues[$key]->value;
            } else {
                $value = self::$memoryValues[$key]->value;
            }
        }

        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $minutes
     *
     * @return mixed
     */
    protected function storeMemoryValue(string $key, $value, $minutes): self
    {
        $memoryValue            = new \stdClass();
        $memoryValue->value     = $value;
        $memoryValue->timestamp = time() + $this->getSeconds($minutes);

        self::$memoryValues[$key] = $memoryValue;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function forgetMemoryValue(string $key): self
    {
        if (array_key_exists($key, self::$memoryValues)) {
            unset(self::$memoryValues[$key]);
        }

        return $this;
    }

    /**
     * Calculate the number of minutes with the given duration.
     *
     * @param  \DateTimeInterface|\DateInterval|float|int $minutes
     *
     * @return float|int|null
     */
    private function getSeconds($minutes)
    {
        $seconds = $this->parseDateInterval($minutes);

        if ($seconds instanceof \DateTimeInterface) {
            $seconds = Carbon::now()->diffInSeconds(Carbon::createFromTimestamp($minutes->getTimestamp()), false);
        } else {
            $seconds *= 60;
        }

        return $seconds;
    }
}