<?php

namespace App\Repository;

use App\Contracts\WeatherDataInterface;
use App\DataProvider\WeatherDataProvider;
use App\Entity\Station;
use App\Entity\Weather;
use Psr\Cache\InvalidArgumentException;

class WeatherRepository
{

    private WeatherDataProvider $weatherDataProvider;

    /**
     * @param WeatherDataProvider $weatherDataProvider
     */
    public function __construct(WeatherDataProvider $weatherDataProvider) {
        $this->weatherDataProvider = $weatherDataProvider;
    }

    /**
     * @param Station $station
     * @return array
     * @throws InvalidArgumentException
     */
    public function getForStation(Station $station): array {
        return array_filter(
            $this->weatherDataProvider->getCollection(Weather::class),
            function (Weather $data) use ($station): bool {
                return $data->getStation()->getId() === $station->getId();
            });
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    public function getAverage(): array {
        $data = $this->weatherDataProvider->getCollection(Weather::class);

        $aggregated = [];
        /** @var WeatherDataInterface $item */
        foreach ($data as $item) {
            $d1 = $item->getTime()->format('z');
            $d2 = $item->getTime()->format('H');
            $aggregated[$d1 * 100 + $d2][] = $item;
        }

        foreach ($aggregated as &$day_data) {
            $day_data = $this->calculateDayAverage($day_data);
        }

        return array_values($aggregated);
    }

    /**
     * @param WeatherDataInterface[] $dayWeatherData
     * @return WeatherDataInterface
     */
    private function calculateDayAverage(array $dayWeatherData): WeatherDataInterface {
        $first = array_shift($dayWeatherData)->toMetric();
        foreach ($dayWeatherData as $item) {
            $first = $first->append($item);
        }
        return $first;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getLatest(?Station $station = null): WeatherDataInterface {
        $collection = is_null($station)
            ? $this->getAverage()
            : $this->getForStation($station);

        return array_pop($collection);
    }
}