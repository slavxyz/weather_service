<?php

namespace App\V1\Application\Weather;

use App\V1\Application\Weather\WeatherFormatterInterface;
use App\V1\Domain\Weather\CurrentWeather;

final class WeatherFormatter implements WeatherFormatterInterface
{    
    public function format(CurrentWeather $weather, float $averageTemperature, string $trendSymbol): array {
        return [
            'temperature' => $weather->temperature()->value(),
            'average'     => $averageTemperature,
            'trend'       => $trendSymbol,
            'coordinates' => [
                'lat' => $weather->coordinates()->latitude(),
                'lon' => $weather->coordinates()->longitude(),
            ],
        ];
    }
}
