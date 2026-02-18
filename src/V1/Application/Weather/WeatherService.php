<?php

namespace App\V1\Application\Weather;

use App\V1\Domain\City\CityFactory;
use App\V1\Domain\Weather\DailyWeatherFactory;
use App\V1\Domain\Weather\WeatherReportFactory;
use App\V1\Infrastructure\Cache\CacheInterface;
use App\V1\Domain\Weather\WeatherProviderInterface;
use DateTimeImmutable;

class WeatherService
{
    public function __construct(
        private WeatherProviderInterface $weatherProvider,
        private CityFactory $cityFactory,
        private DailyWeatherFactory $dailyWeatherFactory,
        private WeatherReportFactory $reportFactory,
        private CacheInterface $cache
    ) {}

    public function getWeatherData(string $cityName): array
    {
        $cacheKey = strtolower($cityName);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $currentWeather = $this->weatherProvider->getCurrentWeather($cacheKey);

        $city = $this->cityFactory->findOrCreate($cityName, $currentWeather->coordinates());

        $this->dailyWeatherFactory->createOrUpdate($city, $currentWeather, new DateTimeImmutable('today'));

        $report = $this->reportFactory->createReport($currentWeather, $city);

        $this->cache->pushToCache($cacheKey, $report);

        return $report;
    }
}
