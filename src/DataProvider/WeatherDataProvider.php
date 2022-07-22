<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use App\Entity\Station;
use App\Entity\StationType;
use App\Entity\Weather;
use Exception;

class WeatherDataProvider implements
    CollectionDataProviderInterface
{
    const APP_DATA_DIR = '/app/data';

    /**
     * @var StationDataProvider
     */
    private $stationDataProvider;

    /**
     * @var
     */
    private $runtimeCache;

    /**
     * @param StationDataProvider $stationDataProvider
     */
    public function __construct(StationDataProvider $stationDataProvider) {
        $this->stationDataProvider = $stationDataProvider;
    }

    /**
     * @throws Exception
     */
    public function getCollection(string $resourceClass, string $operationName = null): iterable {
        $stations = $this->stationDataProvider->getCollection(Station::class);

        foreach ($stations as $station) {
            yield from $this->loadStationData($station);
        }
    }

    /**
     * @param Station $station
     * @return iterable
     * @throws Exception
     */
    protected function loadStationData(Station $station): iterable {
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
            $this->runtimeCache[$station->getId()] = $data;
        }

        return $this->runtimeCache[$station->getId()];
    }

    /**
     * @param string $dirName
     * @param string $mask
     * @param string $ext
     * @return iterable
     */
    protected function getFilesByMask(string $dirName, string $mask, string $ext): iterable {
        $dir = opendir($dirName);
        $files = [];
        while ($file = readdir($dir)) {
            $files[] = $file;
        }

        return array_values(array_filter($files, function (string $f) use ($mask, $ext): bool {
            return substr($f, -strlen($ext) - 1) === ".$ext" && preg_match("/^$mask/", $f);
        }));
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

    /**
     * @param Station $station
     * @return iterable
     * @throws Exception
     */
    public function getStationData(Station $station): iterable {
        return $this->runtimeCache[$station->getId()] ?? $this->loadStationData($station);
    }
}