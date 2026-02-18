<?php 

namespace App\V1\Infrastructure\Weather;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

use App\V1\Domain\Weather\Exception\WeatherProviderUnavailableException;
use App\V1\Infrastructure\Weather\WeatherApiClientInterface;
use App\V1\Domain\City\Coordinates;
use App\V1\Domain\Weather\CurrentWeather;
use App\V1\Domain\Weather\Temperature;

use Throwable;

final class WeatherApiClient implements WeatherApiClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $baseWeatherUrl
    ) {}
    
    /**
     * Get current temperature of city
     */
    public function getCurrentWeatherData(Coordinates $coords): CurrentWeather
    {
        try {
            $response = $this->client->request('GET', $this->baseWeatherUrl . 'forecast', [
                'query' => [
                    'latitude'        => $coords->latitude(),
                    'longitude'       => $coords->longitude(),
                    'current_weather' => true,
                ],
            ]);

            $data = $response->toArray();
        } catch (
            TransportExceptionInterface |
            HttpExceptionInterface |
            Throwable $e
        ) {
            throw new WeatherProviderUnavailableException('Weather API unavailable');
        }

        if (!isset($data['current_weather'])) {
            throw new WeatherProviderUnavailableException('Invalid weather response');
        }

        return new CurrentWeather(
            new Temperature((float) $data['current_weather']['temperature']),
            $coords
        );
    }
}
