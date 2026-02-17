<?php

namespace App\V1\Interfaces\Weather;

use App\V1\Domain\City\Coordinates;

interface GeocodingInterface
{
    public function getCoordinates(string $city): Coordinates;
}
