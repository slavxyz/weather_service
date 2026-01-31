<?php

namespace App\V1\Interfaces\Weather;

interface WeatherProviderInterface
{
    public function getCurrentWeather(string $city): array;
}
