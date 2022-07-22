<?php

namespace App\Repository;

use App\DataProvider\StationDataProvider;
use App\Entity\Station;

class StationRepository
{
    /**
     * @var StationDataProvider
     */
    private $stationDataProvider;

    /**
     * @param StationDataProvider $stationDataProvider
     */
    public function __construct(StationDataProvider $stationDataProvider) {
        $this->stationDataProvider = $stationDataProvider;
    }

    public function getById(int $id): ?Station {
        return $this->stationDataProvider->getItem(Station::class, $id);
    }
}