<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\LatestDataController;
use App\Controller\WeatherAggregateController;
use App\Controller\WeatherController;
use App\Traits\FromArrayTrait;


#[ApiResource(
    collectionOperations: [
        'get',
        'station_weather_aggregate' => [
            'method' => 'GET',
            'openapi_context' => [
                'summary' => 'Retrieves average weather data points for all stations'
                ],
            'path' => '/stations/weather',
            'controller' => WeatherAggregateController::class
        ],
        'latest' => [
            'method' => 'GET',
            'openapi_context' => [
                'summary' => 'Retrieves latest average weather data point'
            ],
            'path' => '/stations/latest',
            'controller' => LatestDataController::class
        ],
    ],
    itemOperations: [
        'get',
        'station_weather' => [
            'method' => 'GET',
            'openapi_context' => [
                'summary' => 'Retrieves weather data points for station with given ID'
            ],
            'path' => '/stations/{id}/weather',
            'controller' => WeatherController::class
        ],
        'station_latest' => [
            'method' => 'GET',
            'openapi_context' => [
                'summary' => 'Retrieves latest weather data point for station with given ID'
            ],
            'path' => '/stations/{id}/latest',
            'controller' => LatestDataController::class
        ],
    ]
)]
class Station
{
    use FromArrayTrait;

    private int $id;

    private string $name;

    private StationType $type;

    /**
     * @param array|null $data
     */
    public function __construct(?array $data = null) {
        if ($data) {
            $data['type'] = new StationType($data['type'] ?: []);
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
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return StationType
     */
    public function getType(): StationType {
        return $this->type;
    }

    /**
     * @param StationType $type
     */
    public function setType(StationType $type): void {
        $this->type = $type;
    }
}