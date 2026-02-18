<?php

namespace App\V1\Domain\Weather;

final class TemperatureTrend
{
    private Temperature $current;
    private Temperature $average;

    public function __construct(
        Temperature $current,
        Temperature $average
    ) {
        $this->current = $current;
        $this->average = $average;
    }

    public function symbol(): string
    {
        if ($this->current->isHigherThan($this->average)) {
            return '🥵';
        }

        if ($this->current->isLowerThan($this->average)) {
            return '🥶';
        }

        return '-';
    }
}
