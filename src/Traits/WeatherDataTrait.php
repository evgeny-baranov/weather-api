<?php

namespace App\Traits;

use App\Contracts\WeatherDataInterface;
use App\Entity\StationType;
use App\Entity\WeatherData;
use DateTime;

trait WeatherDataTrait
{

    protected float $temperature;

    protected float $humidity;

    protected float $wind;

    protected DateTime $time;

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
     * @return WeatherDataInterface
     */
    public function toMetric(): WeatherDataInterface {
        /** @var WeatherDataInterface $converted */
        $converted = new WeatherData();

        $converted->setTime($this->getTime());

        $converted->setUnits(StationType::UNITS_METRIC);

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
     * @return bool
     */
    public function isMetric(): bool {
        return $this->getUnits() == StationType::UNITS_METRIC;
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
     * @return bool
     */
    public function isImperial(): bool {
        return $this->getUnits() == StationType::UNITS_IMPERIAL;
    }

    /**
     * @param WeatherDataInterface $weatherData
     * @return WeatherDataInterface
     */
    public function append(WeatherDataInterface $weatherData): WeatherDataInterface {
        $result = $this->isMetric() ? $this : $this->toMetric();
        $weatherData = $weatherData->toMetric();

        $result->setTemperature(($result->getTemperature() + $weatherData->getTemperature()) / 2);
        $result->setHumidity(($result->getHumidity() + $weatherData->getHumidity()) / 2);
        $result->setWind(($result->getWind() + $weatherData->getWind()) / 2);

        return $result;
    }
}