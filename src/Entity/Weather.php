<?php

namespace App\Entity;

use App\Contracts\WeatherDataInterface;
use App\Traits\FromArrayTrait;
use App\Traits\WeatherDataTrait;
use DateTime;
use Exception;

class Weather implements WeatherDataInterface
{
    use FromArrayTrait;
    use WeatherDataTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var Station
     */
    private $station;

    /**
     * @var float
     */
    private $rain;

    /**
     * @var float
     */
    private $light;

    /**
     * @var string
     */
    private $batteryLevel;

    /**
     * @param array|null $data
     */
    public function __construct(?array $data = null) {
        if ($data) {
            $this->fromArray($data);
        }
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return Station
     */
    public function getStation(): Station {
        return $this->station;
    }

    /**
     * @param Station $station
     */
    public function setStation(Station $station): void {
        $this->station = $station;
    }

    /**
     * TODO: create builder
     * @param array $raw
     * @param Station $station
     * @return Weather
     * @throws Exception
     */
    public function setRawData(array $raw, Station $station): Weather {
        $raw = array_change_key_case($raw);
        $raw['station'] = $station;
        $raw['batteryLevel'] = $raw['battery level'];
        $raw['time'] = strval($raw['time']);
        $time = DateTime::createFromFormat(
            $this->getStationTimeFormat($station),
            $raw['time']
        );

        if (!$time) {
            throw new Exception("Can not convert value {$raw['time']} to DateTime using \"{$this->getStationTimeFormat($station)}\" format");
        }

        $raw['time'] = $time;
        return $this->fromArray($raw);
    }

    /**
     * @param Station $station
     * @return string
     */
    public function getStationTimeFormat(Station $station): string {
        if ($station->getType()->getUnits() == StationType::UNITS_IMPERIAL) {
            return 'U';
        } else {
            return 'd:m:Y H:i:s';
        }
    }

    function getAggregatedData(): AggregatedWeatherData {

    }

    /**
     * @return string
     */
    public function getUnits(): string {
        return $this->getStation()->getType()->getUnits();
    }
}