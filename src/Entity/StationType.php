<?php


namespace App\Entity;

use App\Traits\FromArrayTrait;


class StationType
{
    use FromArrayTrait;

    // TODO: after migrating to PHP8 use enum
    const FORMAT_JSON = 'json';
    const FORMAT_CSV = 'csv';
    const UNITS_IMPERIAL = 'imperial';
    const UNITS_METRIC = 'metric';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $fileNameMask;

    /**
     * @var string
     */
    private $fileFormat;

    /**
     * @var string
     */
    private $units;

    /**
     * @param array|null $data
     */
    public function __construct(?array $data = null) {
        if ($data) {
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
     * @return string
     */
    public function getFileNameMask(): string {
        return $this->fileNameMask;
    }

    /**
     * @param string $fileNameMask
     */
    public function setFileNameMask(string $fileNameMask): void {
        $this->fileNameMask = $fileNameMask;
    }

    /**
     * @return string
     */
    public function getFileFormat(): string {
        return $this->fileFormat;
    }

    /**
     * @param string $fileFormat
     */
    public function setFileFormat(string $fileFormat): void {
        $this->fileFormat = $fileFormat;
    }

    /**
     * @return string
     */
    public function getUnits(): string {
        return $this->units;
    }

    /**
     * @param string $units
     */
    public function setUnits(string $units): void {
        $this->units = $units;
    }
}