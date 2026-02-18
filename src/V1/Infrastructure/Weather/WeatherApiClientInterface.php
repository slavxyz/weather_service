<?php

namespace App\V1\Infrastructure\Weather;

use App\V1\Domain\City\Coordinates;

use App\V1\Domain\Weather\CurrentWeather;

interface WeatherApiClientInterface
{
    public function getCurrentWeatherData(Coordinates $coords): CurrentWeather;
}
