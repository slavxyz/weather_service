<?php

namespace App\V1\Service\Weather;

use App\V1\Repository\DailyWeatherRepository;
use App\V1\Interfaces\Weather\CacheInterface;
use App\V1\Interfaces\Weather\WeatherProviderInterface;
use App\V1\Interfaces\Weather\WeatherFormatterInterface;
use App\V1\Interfaces\Weather\TrendCalculatorInterface;
use App\V1\Service\City\CityCreator;


class WeatherService
{
    public function __construct(
        private WeatherProviderInterface $weatherProvider,
        private WeatherFormatterInterface $formatter,
        private TrendCalculatorInterface $trendCalculator,
        private DailyWeatherRepository $dailyWeatherRepository,
        private CityCreator $cityCreator,
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
        // too many thisng here

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
        // Too many jobs here

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
        $coordinates = $weatherData['coords'];
        $cityEntity  = $this->cityCreator->findOrCreate($city, $coordinates);

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

        // Check if the cache is available

        $averageTemp = $this->dailyWeatherRepository->getLastDaysAverage($cityEntity);
        $result      = $this->formatter->format($weatherData, $averageTemp, $this->trendCalculator);

        $this->cache->pushToCache($city, $result);

        return $result;
    }
}
