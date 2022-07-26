<?php

namespace App\Controller;

use App\Contracts\WeatherDataInterface;
use App\Entity\Weather;
use App\Entity\WeatherData;
use App\Repository\StationRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LatestDataController extends AbstractController
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

    #[Route(
        path: '/stations/{id}/latest',
        name: 'get_station_latest',
        defaults: [
            '_api_resource_class' => WeatherData::class,
            '_api_collection_operation_name' => 'station_latest'
        ],
        methods: ['GET']
    )]
    #[Route(
        path: '/stations/latest',
        name: 'get_latest',
        defaults: [
            '_api_resource_class' => WeatherData::class,
            '_api_collection_operation_name' => 'latest'
        ],
        methods: ['GET']
    )]
    public function __invoke(?int $id = null): WeatherDataInterface {
        return $this->weatherRepository->getLatest(isset($id)
            ? $this->stationRepository->getById($id)
            : null
        );
    }
}