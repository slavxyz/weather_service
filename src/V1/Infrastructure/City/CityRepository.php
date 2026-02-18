<?php

namespace App\V1\Infrastructure\City;

use App\V1\Domain\City\Repository\CityRepositoryInterface;
use App\V1\Domain\City\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CityRepository extends ServiceEntityRepository implements CityRepositoryInterface 
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);

        $this->entityManager = $this->getEntityManager();
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
        $this->entityManager->persist($city);
        $this->entityManager->flush();
    }
}
