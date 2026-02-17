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
     * Find name
     * 
     * @param string $name
     * @return City|null
     */
    public function findByName(string $name): ?City
    {
        return $this->findOneBy(['name' => strtolower($name)]);
    }

    /**
     * Add city
     * 
     * @param City $city
     * @return void
     */
    public function add(City $city): void
    {
        $this->em->persist($city);
        $this->em->flush();
    }
}
