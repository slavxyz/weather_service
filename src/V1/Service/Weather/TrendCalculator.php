<?php

namespace App\V1\Service\Weather;

use App\V1\Interfaces\Weather\TrendCalculatorInterface;

class TrendCalculator implements TrendCalculatorInterface
{
    /**
     * Calculates Trend and returns trend symbol
     *
     * @param float $temp
     * @param float $average
     * @return string
     */
    public function calculate(float $temp, float $average): string
    {
        if ($temp > $average) return '🥵';
        if ($temp < $average) return '🥶';
        return '-';
    }
}
