<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Entity\WeatherData;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherAggregateController extends AbstractController
{
    /**
     * @var WeatherRepository
     */
    private WeatherRepository $weatherRepository;

    /**
     * @param WeatherRepository $weatherRepository
     */
    public function __construct(WeatherRepository $weatherRepository) {
        $this->weatherRepository = $weatherRepository;
    }

    /**
     * @return iterable
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route(
        path: '/stations/weather',
        name: 'get_station_weather_aggregate',
        defaults: [
            '_api_resource_class' => WeatherData::class,
            '_api_collection_operation_name' => 'station_weather_aggregate'
        ],
        methods: ['GET']
    )]
    public function __invoke(): iterable {
        $data = $this->weatherRepository->getAverage();

        /** @var Weather $item */
        foreach ($data as $item) {
            yield $item->toMetric();
        }
    }
}