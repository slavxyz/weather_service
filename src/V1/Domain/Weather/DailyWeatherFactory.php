<?php 

namespace App\V1\Domain\Weather;

use App\V1\Domain\City\Entity\City;
use App\V1\Domain\Weather\Entity\DailyWeather;
use App\V1\Infrastructure\Weather\DailyWeatherRepository;

class DailyWeatherFactory
{
    public function __construct(private DailyWeatherRepository $repository) {}

    public function createOrUpdate(City $city, CurrentWeather $currentWeather, \DateTimeImmutable $date): DailyWeather
    {
        $existing = $this->repository->findOneBy([
            'city' => $city,
            'date' => $date,
        ]);

        $dailyWeather = DailyWeather::recordOrUpdate($existing, $city, $date, $currentWeather->temperature()->value());

        $this->repository->add($dailyWeather);

        return $dailyWeather;
    }
}
