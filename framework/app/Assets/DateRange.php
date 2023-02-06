<?php

namespace App\Assets;

use Carbon\Carbon;

/**
 * Class DateRange
 * @package App\Assets
 *
 * @property string startDate
 * @property string endDate
 */
class DateRange
{
    /**
     * @var Carbon
     */
    private $start;

    /**
     * @var Carbon
     */
    private $end;

    /**
     * @param $startDate
     * @param $endDate
     *
     * @return DateRange
     */
    public static function make($startDate, $endDate): DateRange
    {
        $instance = new static;

        if ($startDate) {
            if (!($startDate instanceof Carbon)) {
                $startDate = Carbon::make($startDate);
            }

            $instance->setStart($startDate);
        }

        if ($endDate) {
            if ($endDate && !($endDate instanceof Carbon)) {
                $endDate = Carbon::make($endDate);
            }

            $instance->setEnd($endDate);
        }

        return $instance;
    }

    /**
     * @return Carbon
     */
    public function getStart(): ?Carbon
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getStartFormatted(): ?string
    {
        return $this->formatDate($this->start);
    }

    /**
     * @param Carbon $start
     *
     * @return DateRange
     */
    public function setStart(Carbon $start): DateRange
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getEnd(): ?Carbon
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getEndFormatted(): ?string
    {
        return $this->formatDate($this->end);
    }

    /**
     * @param Carbon $end
     *
     * @return DateRange
     */
    public function setEnd(Carbon $end): DateRange
    {
        $this->end = $end;

        return $this;
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
            case 'startDate':
                return $this->getStartFormatted();
            case 'endDate':
                return $this->getEndFormatted();
            default:
                throw new \Exception("Unknown property `{$name}` for Country");
        }
    }

    /**
     * @param Carbon|null $date
     *
     * @return null|string
     */
    private function formatDate(Carbon $date = null): ?string
    {
        return !empty($date) ? $date->format('Y-m-d H:i:s') : null;
    }
}