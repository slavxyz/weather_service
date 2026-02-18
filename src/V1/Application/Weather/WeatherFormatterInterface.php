<?php

namespace App\V1\Application\Weather;

use App\V1\Domain\Weather\CurrentWeather;

interface WeatherFormatterInterface
{
    public function format( CurrentWeather $weather, float $averageTemperature, string $trendSymbol): array;
}
