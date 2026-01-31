<?php

namespace App\V1\Interfaces\Weather;

interface TrendCalculatorInterface
{
    public function calculate(float $currentTemp, float $averageTemp): string;
}
