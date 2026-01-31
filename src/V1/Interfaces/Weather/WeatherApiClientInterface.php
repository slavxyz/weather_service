<?php

namespace App\V1\Interfaces\Weather;

interface WeatherApiClientInterface
{
    public function getCurrentWeatherData(array $coords): array;
}
