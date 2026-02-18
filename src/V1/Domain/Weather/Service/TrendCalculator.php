<?php

namespace App\V1\Domain\Weather\Service;

use App\V1\Domain\Weather\Temperature;
use App\V1\Domain\Weather\TemperatureTrend;
use App\V1\Domain\Weather\Service\TrendCalculatorInterface;

final class TrendCalculator implements TrendCalculatorInterface
{
    public function calculate(Temperature $currentTemp, float $averageTemp): string
    {
        $current  = new Temperature($currentTemp->value());
        $average  = new Temperature($averageTemp);

        $trend = new TemperatureTrend($current, $average);

        return $trend->symbol();
    }
}
