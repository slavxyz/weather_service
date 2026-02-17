<?php 

namespace App\V1\Service\Weather;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

use App\V1\Exceptions\Service\Weather\WeatherProviderUnavailableException;
use App\V1\Interfaces\Weather\WeatherApiClientInterface;
use App\V1\Domain\City\Coordinates;

use Throwable;

class WeatherApiClient implements WeatherApiClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $baseWeatherUrl
    ) {}
    
    /**
     * Get current temperature of city
     *
     * @param Coordinates $coords
     * @return void
     */    
    public function getCurrentWeatherData(Coordinates $coords): array
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
        
        return [ 
            'temperature' => $data['current_weather']['temperature'],
            'time' => $data['current_weather']['time']
        ];
    }
}
