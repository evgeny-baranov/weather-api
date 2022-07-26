<?php

namespace App\Traits;

use App\Entity\Station;
use App\Entity\StationType;
use App\Entity\Weather;
use function get_called_class;

trait FromArrayTrait
{
    /**
     * @param array $data
     * @return StationType|Station|Weather|FromArrayTrait
     */
    public function fromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            if (property_exists(get_called_class(), $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }
}