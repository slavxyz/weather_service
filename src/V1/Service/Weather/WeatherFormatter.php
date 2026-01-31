<?php 

namespace App\V1\Service\Weather;

use App\V1\Interfaces\Weather\TrendCalculatorInterface;
use App\V1\Exceptions\Service\Weather\InvalidWeatherDataException;
use App\V1\Interfaces\Weather\WeatherFormatterInterface;
use DateTime;
use DateTimeZone;

class WeatherFormatter implements WeatherFormatterInterface
{
    /**
     * Formattes weather data response
     *
     * @param array $weatherData
     * @param float $averageTemp
     * @param TrendCalculator $trendCalculator
     * @return array
     */
    public function format(array $weatherData, float $averageTemp, TrendCalculatorInterface $trendCalculator): array
    {
        if (!isset($weatherData['temperature'], $weatherData['time'])) {
            throw new InvalidWeatherDataException('Invalid weather data');
        }

        $date = new DateTime($weatherData['time'], new DateTimeZone('UTC'));

        return [
            'temp'  => (float) $weatherData['temperature'],
            'trend' => $trendCalculator->calculate((float) $weatherData['temperature'], $averageTemp),
            'date'  => $date->format('M j'),
            'day'   => $date->format('l')
        ];
    }

}
