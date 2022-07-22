<?php

namespace App\Entity;

use App\Contracts\WeatherDataInterface;
use App\Traits\WeatherDataTrait;

class AggregatedWeatherData implements WeatherDataInterface
{
    use WeatherDataTrait;

    /**
     * @var string
     */
    protected $units;

    /**
     * @return string
     */
    public function getUnits(): string {
        return $this->units;
    }

    /**
     * @return bool
     */
    public function isImperial(): bool {
        return $this->getUnits() == StationType::UNITS_IMPERIAL;
    }
}