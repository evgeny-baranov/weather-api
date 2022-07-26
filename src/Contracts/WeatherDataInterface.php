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

    /**
     * @param WeatherDataInterface $weatherData
     * @return WeatherDataInterface
     */
    public function append(WeatherDataInterface $weatherData): WeatherDataInterface;

    /**
     * @return bool
     */
    public function isImperial(): bool;

    /**
     * @return bool
     */
    public function isMetric(): bool;

    /**
     * @param DateTime $time
     * @return void
     */
    public function setTime(DateTime $time): void;

    /**
     * @param float $humidity
     * @return void
     */
    public function setHumidity(float $humidity): void;

    /**
     * @param float $wind
     * @return void
     */
    public function setWind(float $wind): void;

    /**
     * @param float $temperature
     * @return void
     */
    public function setTemperature(float $temperature): void;

    /**
     * @param string $units
     * @return void
     */
    public function setUnits(string $units): void;

    /**
     * @return WeatherDataInterface
     */
    public function toMetric(): WeatherDataInterface;
}