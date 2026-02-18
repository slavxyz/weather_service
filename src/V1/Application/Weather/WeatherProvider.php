<?php

namespace App\V1\Application\Weather;

use App\V1\Domain\Weather\CurrentWeather;
use App\V1\Domain\City\GeocodingInterface;
use App\V1\Infrastructure\Weather\WeatherApiClientInterface;
use App\V1\Domain\Weather\Exception\TemperatureNotFoundException;
use App\V1\Domain\Weather\WeatherProviderInterface;
use App\V1\Domain\Weather\Temperature;

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
     * @return CurrentWeather
     */
    public function getCurrentWeather(string $city): CurrentWeather
    {
        $coords = $this->geocodingService->getCoordinates($city);
        $data = $this->weatherApiClient->getCurrentWeatherData($coords);
        
        if (empty($data)) {
            throw new TemperatureNotFoundException("Temperature not found for city $city");
        }

        return new CurrentWeather(
            new Temperature((float) $data->temperature()->value()),
            $coords
        );
    }
}
