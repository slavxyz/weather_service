<?php

namespace App\V1\Domain\Weather;

use App\V1\Domain\Weather\CurrentWeather;

interface WeatherProviderInterface
{
    public function getCurrentWeather(string $city): CurrentWeather;
}
