<?php

namespace App\V1\Domain\City;

final class Coordinates
{
    public function __construct(
        private float $latitude,
        private float $longitude
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new \InvalidArgumentException('Invalid latitude');
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new \InvalidArgumentException('Invalid longitude');
        }
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }
}
