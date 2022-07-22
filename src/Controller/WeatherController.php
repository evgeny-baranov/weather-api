<?php

namespace App\Controller;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Repository\StationRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Station;
use App\Entity\Weather;

class WeatherController extends AbstractController
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
     *     name="station_weather_aggregate",
     *     methods={"GET"},
     *     defaults={
     *        "_api_resource_class"=Weather::class,
     *        "_api_collection_operation_name"="station_weather"
     *     }
     *  )
     * @param int|null $id
     * @return iterable|void
     * @throws ResourceClassNotSupportedException
     */
    public function __invoke(?int $id = null): iterable {
        return $this->weatherRepository->getAll();
    }
}