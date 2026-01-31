<?php

namespace App\Tests\V1\Service;

use PHPUnit\Framework\TestCase;
use App\V1\Service\Weather\TrendCalculator;

class TrendCalculatorTest extends TestCase
{
    public function testHotTrend(): void
    {
        $calculator = new TrendCalculator();
        $this->assertSame('🥵', $calculator->calculate(25.0, 20.0));
    }

    public function testColdTrend(): void
    {
        $calculator = new TrendCalculator();
        $this->assertSame('🥶', $calculator->calculate(15.0, 20.0));
    }

    public function testEqualTrend(): void
    {
        $calculator = new TrendCalculator();
        $this->assertSame('-', $calculator->calculate(20.0, 20.0));
    }
}
