<?php

namespace App\Traits;

trait FromArrayTrait
{
    /**
     * @param array $data
     * @return FromArrayTrait
     */
    public function fromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            if (property_exists(\get_called_class(), $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }
}