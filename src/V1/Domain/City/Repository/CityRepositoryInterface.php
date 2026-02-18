<?php

namespace App\V1\Domain\City\Repository;

use App\V1\Domain\City\Entity\City;

interface CityRepositoryInterface
{
    public function findByName(string $name): ?City;

    public function add(City $city): void;
}
