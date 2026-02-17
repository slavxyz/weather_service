<?php

namespace App\V1\Service\Weather;

use App\V1\Domain\Weather\Temperature;
use App\V1\Domain\Weather\TemperatureTrend;
use App\V1\Interfaces\Weather\TrendCalculatorInterface;

final class TrendCalculator implements TrendCalculatorInterface
{
    public function calculate(float $currentTemp, float $averageTemp): string
    {
        $current  = new Temperature($currentTemp);
        $average  = new Temperature($averageTemp);

        $trend = new TemperatureTrend($current, $average);

        return $trend->symbol();
    }
}
