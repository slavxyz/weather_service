<?php

namespace App\Tests\V1\Service\Weather;

use PHPUnit\Framework\TestCase;
use App\V1\Service\Weather\WeatherFormatter;
use App\V1\Service\Weather\TrendCalculator;

class WeatherFormatterTest extends TestCase
{
    private WeatherFormatter $formatter;
    private TrendCalculator $trendCalculatorMock;

    protected function setUp(): void
    {
        $this->formatter = new WeatherFormatter();
        $this->trendCalculatorMock = $this->createMock(TrendCalculator::class);
    }

    public function testFormatReturnsCorrectData(): void
    {
        date_default_timezone_set('UTC');

        $weatherData = [
            'temperature' => 25.5,
            'time' => '2026-01-28 14:00:00'
        ];
        $averageTemp = 20.0;

        $this->trendCalculatorMock
            ->method('calculate')
            ->with($weatherData['temperature'], $averageTemp)
            ->willReturn('up');

        $result = $this->formatter->format($weatherData, $averageTemp, $this->trendCalculatorMock);

        $expectedData = [
            'temp'  => 25.5,
            'trend' => 'up',
            'date'  => 'Jan 28',
            'day'   => 'Wednesday'
        ];


        $this->assertEquals($expectedData, $result);
    }
}