<?php

namespace App\Controller;

use App\Entity\WeatherData;
use App\Repository\StationRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private WeatherRepository $weatherRepository;

    private StationRepository $stationRepository;

    /**
     * @param WeatherRepository $weatherRepository
     * @param StationRepository $stationRepository
     */
    public function __construct(WeatherRepository $weatherRepository, StationRepository $stationRepository) {
        $this->weatherRepository = $weatherRepository;
        $this->stationRepository = $stationRepository;
    }

    /**
     * @param int $id
     * @return iterable
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route(
        path: '/stations/{id}/weather',
        name: 'get_station_weather',
        defaults: [
            '_api_resource_class' => WeatherData::class,
            '_api_collection_operation_name' => 'station_weather'
        ],
        methods: ['GET']
    )]
    public function __invoke(int $id): iterable {
        return $this->weatherRepository->getForStation($this->stationRepository->getById($id));
    }
}