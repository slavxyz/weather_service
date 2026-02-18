<?php

namespace App\V1\Domain\Weather\Repository;

use App\V1\Domain\City\Entity\City;
use App\V1\Domain\Weather\Entity\DailyWeather;

interface DailyWeatherRepositoryInterface
{
    public function add(DailyWeather $dailyWeather): void;

    public function findLastDays(City $city, int $days): float;
}
