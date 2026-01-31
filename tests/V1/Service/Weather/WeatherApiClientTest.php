<?php

namespace App\Tests\V1\Service\Weather;

use PHPUnit\Framework\TestCase;
use App\V1\Service\Weather\WeatherApiClient;
use App\V1\Exceptions\Service\Weather\WeatherProviderUnavailableException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherApiClientTest extends TestCase
{
    private $httpClientMock;
    private WeatherApiClient $apiClient;
    private string $baseUrl = 'https://api.example.com/';

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->apiClient = new WeatherApiClient($this->httpClientMock, $this->baseUrl);
    }

    public function testGetCurrentWeatherDataSuccess(): void
    {
        $coords = ['lat' => 51.5074, 'lon' => -0.1278];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn([
            'current_weather' => [
                'temperature' => 15.5,
                'time' => '2026-01-28 14:00:00'
            ]
        ]);

        $this->httpClientMock->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                $this->baseUrl . 'forecast',
                ['query' => [
                    'latitude' => $coords['lat'],
                    'longitude' => $coords['lon'],
                    'current_weather' => true
                ]]
            )
            ->willReturn($responseMock);

        $result = $this->apiClient->getCurrentWeatherData($coords);

        $this->assertEquals([
            'temperature' => 15.5,
            'time' => '2026-01-28 14:00:00'
        ], $result);
    }

    public function testGetCurrentWeatherDataThrowsExceptionOnMissingData(): void
    {
        $coords = ['lat' => 40, 'lon' => -70];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn([]); 

        $this->httpClientMock->method('request')->willReturn($responseMock);

        $this->expectException(WeatherProviderUnavailableException::class);
        $this->expectExceptionMessage('Invalid weather response');

        $this->apiClient->getCurrentWeatherData($coords);
    }

    public function testGetCurrentWeatherDataThrowsExceptionOnHttpClientError(): void
    {
        $coords = ['lat' => 35, 'lon' => 139];

        $this->httpClientMock->method('request')
            ->willThrowException($this->createMock(TransportExceptionInterface::class));

        $this->expectException(WeatherProviderUnavailableException::class);
        $this->expectExceptionMessage('Weather API unavailable');

        $this->apiClient->getCurrentWeatherData($coords);
    }
}
