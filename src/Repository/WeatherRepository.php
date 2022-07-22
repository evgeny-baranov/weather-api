<?php

namespace App\Repository;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\WeatherDataProvider;
use App\Entity\Station;
use App\Entity\Weather;
use Exception;

class WeatherRepository
{
    /**
     * @var WeatherDataProvider
     */
    private $weatherDataProvider;

    /**
     * @param WeatherDataProvider $weatherDataProvider
     */
    public function __construct(WeatherDataProvider $weatherDataProvider) {
        $this->weatherDataProvider = $weatherDataProvider;
    }

    /**
     * @param Station $station
     * @return iterable
     * @throws Exception
     */
    public function getForStation(Station $station): iterable {
        return new \CallbackFilterIterator(
            $this->weatherDataProvider->getCollection(Weather::class),
            function (Weather $data, $key, $iterator) use ($station): bool {
                return $data->getStation()->getId() === $station->getId();
            });
    }

    /**
     * @return iterable
     * @throws Exception
     */
    public function getAll(): iterable {
        return $this->weatherDataProvider->getCollection(Weather::class);
    }
}