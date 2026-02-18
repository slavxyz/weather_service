<?php

namespace App\V1\Domain\Weather;

use App\V1\Domain\City\Coordinates;

final class CurrentWeather
{
    public function __construct(
        private Temperature $temperature,
        private Coordinates $coordinates
    ) {
    }

    public function temperature(): Temperature
    {
        return $this->temperature;
    }

    public function coordinates(): Coordinates
    {
        return $this->coordinates;
    }
}
