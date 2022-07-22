<?php

namespace App\Contracts;

use DateTime;

interface WeatherDataInterface
{
    /**
     * @return DateTime
     */
    public function getTime(): DateTime;

    /**
     * @return float
     */
    public function getTemperature(): float;

    /**
     * @return float
     */
    public function getHumidity(): float;

    /**
     * @return float
     */
    public function getWind(): float;

    /**
     * @return string
     */
    public function getUnits(): string;
}