<?php

namespace App\Controller;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Repository\StationRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherAggregateController extends AbstractController
{
    /**
     * @var WeatherRepository
     */
    private $weatherRepository;

    /**
     * @var StationRepository
     */
    private $stationRepository;

    /**
     * @param WeatherRepository $weatherRepository
     * @param StationRepository $stationRepository
     */
    public function __construct(WeatherRepository $weatherRepository, StationRepository $stationRepository) {
        $this->weatherRepository = $weatherRepository;
        $this->stationRepository = $stationRepository;
    }

    /**
     * @Route(
     *     path="/stations/weather",
     *     name="get_station_weather_aggregate",
     *     methods={"GET"},
     *     defaults={
     *        "_api_resource_class"=Weather::class,
     *        "_api_collection_operation_name"="station_weather_aggregate"
     *     }
     *  )
     * @param int $id
     * @return iterable
     * @throws ResourceClassNotSupportedException
     */
    public function __invoke(): iterable {
            return $this->weatherRepository->getForStation($this->stationRepository->getById($id));
    }
}