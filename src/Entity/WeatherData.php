<?php

namespace App\Entity;

use App\Contracts\WeatherDataInterface;
use App\Traits\WeatherDataTrait;

class WeatherData implements WeatherDataInterface
{
    use WeatherDataTrait;

    protected string $units;

    /**
     * @return string
     */
    public function getUnits(): string {
        return $this->units;
    }

    /**
     * @param string $units
     */
    public function setUnits(string $units): void {
        $this->units = $units;
    }
}