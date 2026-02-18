<?php

namespace App\V1\Domain\Weather\Service;;
use App\V1\Domain\Weather\Temperature;

interface TrendCalculatorInterface
{
    public function calculate(Temperature $currentTemp, float $averageTemp): string;
}
