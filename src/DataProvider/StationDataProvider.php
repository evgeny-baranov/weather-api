<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Station;
use App\Entity\StationType;

class StationDataProvider implements
    ContextAwareCollectionDataProviderInterface,
    ItemDataProviderInterface,
    RestrictedDataProviderInterface
{

    private $data = [
        [
            'id' => 1,
            'name' => 'Station 1',
            'type' => [
                'id' => 1,
                // YYYY-DD-MM
                'fileNameMask' => '\d{4}-\d{2}-\d{2}',
                'fileFormat' => StationType::FORMAT_JSON,
                'units' => StationType::UNITS_IMPERIAL
            ]
        ],
        [
            'id' => 2,
            'name' => 'Station 2',
            'type' => [
                'id' => 1,
                // DD-MM-YYYY
                'fileNameMask' => '\d{2}-\d{2}-\d{4}',
                'fileFormat' => StationType::FORMAT_CSV,
                'units' => StationType::UNITS_METRIC
            ]
        ],
    ];

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return iterable
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable {
        foreach ($this->data as $item) {
            yield new Station($item);
        }
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return Station::class === $resourceClass;
    }


    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array $context
     * @return Station|mixed|object|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []) {
        foreach ($this->getCollection($resourceClass) as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }
}