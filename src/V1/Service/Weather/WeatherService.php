<?php

namespace App\V1\Service\Weather;

use App\V1\Repository\CityRepository;
use App\V1\Repository\DailyWeatherRepository;
use App\V1\Interfaces\Weather\CacheInterface;
use App\V1\Interfaces\Weather\WeatherProviderInterface;
use App\V1\Interfaces\Weather\WeatherFormatterInterface;
use App\V1\Interfaces\Weather\TrendCalculatorInterface;

class WeatherService
{
    public function __construct(
        private WeatherProviderInterface $weatherProvider,
        private WeatherFormatterInterface $formatter,
        private TrendCalculatorInterface $trendCalculator,
        private CityRepository $cityRepository,
        private DailyWeatherRepository $dailyWeatherRepository,
        private CacheInterface $cache
    ) {}
    
    /**
     * Returns Weather data
     *
     * @param string $city
     * @return array
     */
    public function getWeatherData(string $city): array
    {
        $city = strtolower($city);
        $cacheItem = $this->cache->getItem($city);

        return $cacheItem->isHit()
            ? $cacheItem->get()
            : $this->fetchAndProcessWeather($city);
    }

    /**
     * Process Weather Data
     *
     * @param string $city
     * @return array
     */
    private function fetchAndProcessWeather(string $city): array
    {
        $weatherData = $this->fetchWeather($city);
        $cityEntity  = $this->saveDailyWeather($city, $weatherData);
        $result      = $this->formatAndCacheWeather($city, $weatherData, $cityEntity);

        return $result;
    }
    
    /**
     * Fetch current data
     *
     * @param string $city
     * @return array
     */
    private function fetchWeather(string $city): array
    {
        return $this->weatherProvider->getCurrentWeather($city);
    }

    /**
     * Save city and related data
     *
     * @param string $city
     * @param array $weatherData
     * @return object
     */
    private function saveDailyWeather(string $city, array $weatherData): object
    {
        $cityEntity = $this->cityRepository->findOrCreate($city, $weatherData['coords']);
        $this->dailyWeatherRepository->saveToday($cityEntity, $weatherData['temperature']);

        return $cityEntity;
    }

    /**
     * Format and cache weather data
     *
     * @param string $city
     * @param array $weatherData
     * @param object $cityEntity
     * @return array
     */
    private function formatAndCacheWeather(string $city, array $weatherData, object $cityEntity): array
    {
        $averageTemp = $this->dailyWeatherRepository->getLastDaysAverage($cityEntity);
        $result      = $this->formatter->format($weatherData, $averageTemp, $this->trendCalculator);

        $this->cache->pushToCache($city, $result);

        return $result;
    }
}
