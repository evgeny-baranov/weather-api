<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use App\Contracts\WeatherDataInterface;
use App\Entity\Station;
use App\Entity\StationType;
use App\Entity\Weather;
use CallbackFilterIterator;
use DirectoryIterator;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class WeatherDataProvider implements
    CollectionDataProviderInterface
{
    const APP_DATA_DIR = '/app/data';

    private StationDataProvider $stationDataProvider;
    private FilesystemAdapter $cache;

    public function __construct(StationDataProvider $stationDataProvider) {
        $this->stationDataProvider = $stationDataProvider;
        $this->cache = new FilesystemAdapter();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function getCollection(string $resourceClass, string $operationName = null): array {
        return $this->cache->get(__METHOD__, function (ItemInterface $item) {
            $item->expiresAfter(5 * 60 * 60);

            $collection = [];
            $stations = $this->stationDataProvider->getCollection(Station::class);
            foreach ($stations as $station) {
                $collection = array_merge($collection, $this->loadStationData($station));
            }
            usort($collection, function (WeatherDataInterface $item1, WeatherDataInterface $item2) {
                return $item1->getTime() <=> $item2->getTime();
            });

            return $collection;
        });
    }

    /**
     * @param Station $station
     * @return array
     * @throws Exception
     */
    protected function loadStationData(Station $station): array {
        $files = $this->getFilesByMask(
            self::APP_DATA_DIR,
            $station->getType()->getFileNameMask(),
            $station->getType()->getFileFormat()
        );

        $data = [];
        foreach ($files as $file_name) {
            $file_name = self::APP_DATA_DIR . DIRECTORY_SEPARATOR . $file_name;
            $content = file_get_contents($file_name);
            if ($station->getType()->getFileFormat() == StationType::FORMAT_JSON) {
                $daily_data = json_decode($content, JSON_OBJECT_AS_ARRAY);
            } else {
                $daily_data = array_map('str_getcsv', file($file_name));
                $header = array_shift($daily_data);

                foreach ($daily_data as &$item) {
                    $item = array_combine($header, $item);
                }
            }

            foreach ($daily_data as &$item) {
                $item = (new Weather())->setRawData($item, $station);
            }
            $data = array_merge($data, $daily_data);
        }

        return $data;
    }

    /**
     * @param string $dirName
     * @param string $mask
     * @param string $ext
     * @return iterable
     */
    protected function getFilesByMask(string $dirName, string $mask, string $ext): iterable {
        return yield from (new CallbackFilterIterator(
            new DirectoryIterator($dirName),
            function (DirectoryIterator $fileInfo) use ($mask, $ext): bool {
                return $fileInfo->isFile()
                    && $fileInfo->getExtension() == $ext
                    && preg_match("/^$mask/", $fileInfo->getFilename());
            }
        ));
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return Weather::class === $resourceClass;
    }
}