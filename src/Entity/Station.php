<?php

namespace App\Entity;

use App\Traits\FromArrayTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Controller\WeatherController;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"
 *     },
 *     itemOperations={
 *         "get",
 *         "station_weather" = {
 *              "method"="GET",
 *              "path"="/stations/{id}/weather",
 *              "controller"=WeatherController::class
 *          },
 *         "station_weather_aggregate" = {
 *              "method"="GET",
 *              "path"="/stations/weather",
 *              "controller"=WeatherAggregateController::class
 *          }
 *     },
 * )
 */
class Station
{
    use FromArrayTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @ApiProperty(attributes={"openapi_context"={"type"="string"}})
     */
    private $name;

    /**
     * @var StationType
     */
    private $type;

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
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void {
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