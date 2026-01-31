<?php

namespace App\V1\Interfaces\Weather;

use App\V1\Interfaces\Weather\TrendCalculatorInterface;

interface WeatherFormatterInterface
{
    public function format(array $weatherData, float $averageTemp, TrendCalculatorInterface $trendCalculator): array;
}
