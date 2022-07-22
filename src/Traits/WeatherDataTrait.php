<?php

namespace App\Traits;

use App\Entity\AggregatedWeatherData;
use App\Entity\StationType;
use DateTime;

trait WeatherDataTrait
{
    /**
     * @var float
     */
    protected $temperature;

    /**
     * @var float
     */
    protected $humidity;

    /**
     * @var float
     */
    protected $wind;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @return float
     */
    public function getTemperature(): float {
        return $this->temperature;
    }

    /**
     * @param float $temperature
     */
    public function setTemperature(float $temperature): void {
        $this->temperature = $temperature;
    }

    /**
     * @return float
     */
    public function getWind(): float {
        return $this->wind;
    }

    /**
     * @param float $wind
     */
    public function setWind(float $wind): void {
        $this->wind = $wind;
    }

    /**
     * @return float
     */
    public function getHumidity(): float {
        return $this->humidity;
    }

    /**
     * @param float $humidity
     */
    public function setHumidity(float $humidity): void {
        $this->humidity = $humidity;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return void
     */
    public function setTime(DateTime $time): void {
        $this->time = $time;
    }

    /**
     * @return bool
     */
    public function isMetric(): bool {
        return $this->getUnits() == StationType::UNITS_METRIC;
    }

    /**
     * @return AggregatedWeatherData
     */
    public function convertToMetric(): AggregatedWeatherData {
        $converted = new AggregatedWeatherData();

        $converted->setHumidity($this->getHumidity());

        $converted->setWind($this->isMetric()
            ? $this->getWind()
            : $this->getWind() * 1.60934
        );

        $converted->setTemperature($this->isMetric()
            ? $this->getTemperature()
            : ($this->getTemperature() - 32) * 5 / 9
        );

        return $converted;
    }
}