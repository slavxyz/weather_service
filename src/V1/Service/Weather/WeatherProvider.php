<?php

namespace App\V1\Service\Weather;

use App\V1\Interfaces\Weather\GeocodingInterface;
use App\V1\Interfaces\Weather\WeatherApiClientInterface;
use App\V1\Exceptions\Service\Weather\TemperatureNotFoundException;
use App\V1\Interfaces\Weather\WeatherProviderInterface;

class WeatherProvider implements WeatherProviderInterface
{
    public function __construct(
        private GeocodingInterface $geocodingService,
        private WeatherApiClientInterface $weatherApiClient
    ) {}
    
    /**
     * Get Weather data from API 
     *
     * @param string $city
     * @return array
     */
    public function getCurrentWeather(string $city): array
    {
        $coords = $this->geocodingService->getCoordinates($city);
        $weatherData = $this->weatherApiClient->getCurrentWeatherData($coords);

        if (empty($weatherData)) {
            throw new TemperatureNotFoundException("Temperature not found for city $city");
        }

        $weatherData['coords'] = $coords;

        return $weatherData;
    }
}
