<?php

namespace App\V1\Repository;

use App\V1\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CityRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);

        $this->em = $this->getEntityManager();
    }

    /**
     * Returns city or create new record in city table
     *
     * @param string $name
     * @param array $coords
     * @return City
     */
    public function findOrCreate(string $name, array $coords): City
    {
        $city = $this->findOneBy(['name' => $name]);

        if ($city) {
            return $city;
        }

        $city = new City($name, $coords['lat'], $coords['lon']);

        $this->em->persist($city);
        $this->em->flush();

        return $city;
    }
}
