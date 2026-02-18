<?php

namespace App\V1\Domain\Weather;

use App\V1\Application\Weather\WeatherFormatterInterface;
use App\V1\Domain\Weather\Service\TrendCalculatorInterface;
use App\V1\Domain\City\Entity\City;
use App\V1\Infrastructure\Weather\DailyWeatherRepository;
use DateTimeImmutable;

class WeatherReportFactory
{
    public function __construct(
        private TrendCalculatorInterface $trendCalculator,
        private WeatherFormatterInterface $formatter,
        private DailyWeatherRepository $dailyWeatherRepository
    ) {}

    public function createReport(CurrentWeather $currentWeather, City $city): array
    {
        $averageTemp = $this->dailyWeatherRepository->findLastDays($city);

        $trendSymbol = $this->trendCalculator->calculate($currentWeather->temperature(), $averageTemp);

        $report = new WeatherReport($currentWeather, $averageTemp, $trendSymbol);

        return $report->formatted($this->formatter);

      
    }
}
