<?php

namespace App\V1\Domain\Weather;

use App\V1\Application\Weather\WeatherFormatterInterface;

class WeatherReport
{
    public function __construct(
        private CurrentWeather $current,
        private float $averageTemp,
        private string $trendSymbol
    ) {}

    public function formatted(WeatherFormatterInterface $formatter): array
    {
        return $formatter->format($this->current, $this->averageTemp, $this->trendSymbol);
    }
}
