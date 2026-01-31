<?php

namespace App\Tests\V1\Service\Weather;

use PHPUnit\Framework\TestCase;
use App\V1\Service\Weather\WeatherProvider;
use App\V1\Service\Weather\GeocodingService;
use App\V1\Service\Weather\WeatherApiClient;
use App\V1\Exceptions\Service\Weather\TemperatureNotFoundException;

class WeatherProviderTest extends TestCase
{
    public function testReturnsWeatherDataWithCoordinates(): void
    {
        $city = 'Berlin';
        $coords = ['lat' => 52.52, 'lon' => 13.405];
        $apiData = ['temperature' => 25];

        $geoMock = $this->createMock(GeocodingService::class);
        $geoMock
            ->method('getCoordinates')
            ->with($city)
            ->willReturn($coords);

        
        $apiMock = $this->createMock(WeatherApiClient::class);
        $apiMock
            ->method('getCurrentWeatherData')
                ->with($coords)
                ->willReturn($apiData);

        $provider = new WeatherProvider($geoMock, $apiMock);

        $result = $provider->getCurrentWeather($city);

        $this->assertSame([
            'temperature' => 25,
            'coords' => $coords
        ], $result);
    }

    public function testThrowsExceptionIfApiReturnsEmptyArray(): void
    {
        $city = 'Berlin';
        $coords = ['lat' => 52.52, 'lon' => 13.405];

        $geoMock = $this->createMock(GeocodingService::class);
        $geoMock
            ->method('getCoordinates')
            ->with($city)
            ->willReturn($coords);

        $apiMock = $this->createMock(WeatherApiClient::class);
        $apiMock
            ->method('getCurrentWeatherData')
            ->with($coords)
            ->willReturn([]); 

        $provider = new WeatherProvider($geoMock, $apiMock);

        $this->expectException(TemperatureNotFoundException::class);
        $this->expectExceptionMessage("Temperature not found for city $city");

        $provider->getCurrentWeather($city);
    }

}
