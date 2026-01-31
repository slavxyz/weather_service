<?php

namespace App\V1\Interfaces\Weather; 

interface GeocodingInterface
{
    public function getCoordinates(string $city): array;
}
