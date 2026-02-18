<?php 
namespace App\V1\Domain\City;

use App\V1\Domain\City\Entity\City;
use App\V1\Domain\City\Repository\CityRepositoryInterface;

class CityFactory
{
    public function __construct(private CityRepositoryInterface $cityRepository) {}

    public function findOrCreate(string $name, Coordinates $coordinates): City
    {
        $city = $this->cityRepository->findByName($name);

        if (!$city) {
            $city = new City($name, $coordinates);
            $this->cityRepository->add($city);
        }

        return $city;
    }
}
