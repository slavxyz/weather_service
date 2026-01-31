<?php

namespace App\Tests\V1\Service\Weather;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\ItemInterface;
use App\V1\Service\Weather\WeatherService;
use App\V1\Service\RedisCacheService;
use App\V1\Interfaces\Weather\WeatherProviderInterface;
use App\V1\Interfaces\Weather\WeatherFormatterInterface;
use App\V1\Interfaces\Weather\TrendCalculatorInterface;
use App\V1\Interfaces\Weather\CacheInterface;
use App\V1\Repository\CityRepository;
use App\V1\Repository\DailyWeatherRepository;
use App\V1\Entity\City;

class WeatherServiceTest extends TestCase
{
    private WeatherService $weatherService;
    private WeatherProviderInterface $weatherProvider;
    private WeatherFormatterInterface $formatter;
    private TrendCalculatorInterface $trendCalculator;
    private CityRepository $cityRepository;
    private DailyWeatherRepository $dailyWeatherRepository;
    private CacheInterface $cache;

    protected function setUp(): void
    {
        $this->weatherProvider = $this->createMock(WeatherProviderInterface::class);
        $this->formatter = $this->createMock(WeatherFormatterInterface::class);
        $this->trendCalculator = $this->createMock(TrendCalculatorInterface::class);
        $this->cityRepository = $this->createMock(CityRepository::class);
        $this->dailyWeatherRepository = $this->createMock(DailyWeatherRepository::class);
        $this->cache = $this->createMock(CacheInterface::class);

        $this->weatherService = new WeatherService(
            $this->weatherProvider,
            $this->formatter,
            $this->trendCalculator,
            $this->cityRepository,
            $this->dailyWeatherRepository,
            $this->cache
        );
    }

    public function testGetWeatherDataReturnsCachedData(): void
    {
        $city = 'London';
        $cachedData = ['temp' => 20, 'trend' => 'up', 'day' => 'Monday'];

        $cacheItemMock = $this->createMock(ItemInterface::class);
        $cacheItemMock
            ->method('isHit')
            ->willReturn(true);

        $cacheItemMock
            ->method('get')
            ->willReturn($cachedData);

        $cacheMock = $this->createMock(RedisCacheService::class);

        $cacheMock
            ->expects($this->once())
            ->method('getItem')
            ->with(strtolower($city))
            ->willReturn($cacheItemMock);

        $weatherService = new WeatherService(
            $this->weatherProvider,
            $this->formatter,
            $this->trendCalculator,
            $this->cityRepository,
            $this->dailyWeatherRepository,
            $cacheMock
        );

        $result = $weatherService->getWeatherData($city);

        $this->assertSame($cachedData, $result);
    }


    public function testGetWeatherDataFetchesAndProcessesIfCacheMiss(): void
    {
        $city = 'Paris';

        $coords = ['lat' => 48.8566, 'lon' => 2.3522];

        $weatherData = [
            'temperature' => 15,
            'time'        => '2026-01-30T12:00:00Z',
            'coords'      => $coords,
        ];

        $formattedData = [
            'temp'  => 15,
            'trend' => 'stable',
            'day'   => 'Thursday',
        ];

        $cacheItemMock = $this->createMock(ItemInterface::class);
        $cacheItemMock
            ->method('isHit')
            ->willReturn(false);

        $cacheMock = $this->createMock(RedisCacheService::class);

        $cacheMock
            ->expects($this->once())
            ->method('getItem')
            ->with(strtolower($city))
            ->willReturn($cacheItemMock);

        $cacheMock
            ->expects($this->once())
            ->method('pushToCache')
            ->with(strtolower($city), $formattedData);

        $this->weatherProvider
            ->expects($this->once())
            ->method('getCurrentWeather')
            ->with(strtolower($city))
            ->willReturn($weatherData);

        $cityEntity = $this->createMock(City::class);

        $this->cityRepository
            ->expects($this->once())
            ->method('findOrCreate')
            ->with(strtolower($city), $coords)
            ->willReturn($cityEntity);

        $this->dailyWeatherRepository
            ->expects($this->once())
            ->method('saveToday')
            ->with($cityEntity, 15);

        $this->dailyWeatherRepository
            ->expects($this->once())
            ->method('getLastDaysAverage')
            ->with($cityEntity)
            ->willReturn(14.5);

        $this->formatter
            ->expects($this->once())
            ->method('format')
            ->with($weatherData, 14.5, $this->trendCalculator)
            ->willReturn($formattedData);

        $weatherService = new WeatherService(
            $this->weatherProvider,
            $this->formatter,
            $this->trendCalculator,
            $this->cityRepository,
            $this->dailyWeatherRepository,
            $cacheMock
        );

        $result = $weatherService->getWeatherData($city);

        $this->assertSame($formattedData, $result);
    }

}
