<?php

namespace App\V1\Interfaces\Weather;

use App\V1\Domain\City\Coordinates;

interface WeatherApiClientInterface
{
    public function getCurrentWeatherData(Coordinates $coords): array;
}
