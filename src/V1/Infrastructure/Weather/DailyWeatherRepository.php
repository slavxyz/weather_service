<?php

namespace App\V1\Infrastructure\Weather;

use App\V1\Domain\Weather\Repository\DailyWeatherRepositoryInterface;
use App\V1\Domain\Weather\Entity\DailyWeather;
use App\V1\Domain\City\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

final class DailyWeatherRepository extends ServiceEntityRepository implements DailyWeatherRepositoryInterface
{
    public const HISTORY_LIMIT_DAYS = 10;

    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyWeather::class);

        $this->entityManager = $this->getEntityManager();
    }

    /**
     * Persist a DailyWeather record
     */
    public function add(DailyWeather $dailyWeather): void
    {
        $this->entityManager->persist($dailyWeather);
        $this->entityManager->flush();

        $this->trimHistory($dailyWeather->city());
    }

    /**
     * @return float
     */
    public function findLastDays(City $city, int $days = self::HISTORY_LIMIT_DAYS): float
    {
        return $this->createQueryBuilder('dw')
            ->select('AVG(dw.temperature)')
            ->andWhere('dw.city = :city')
            ->setParameter('city', $city)
            ->orderBy('dw.date', 'DESC')
            ->setMaxResults($days)
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function trimHistory(City $city): void
    {
        $thresholdDate = new DateTimeImmutable(
            sprintf('-%d days', self::HISTORY_LIMIT_DAYS)
        );

        $this->createQueryBuilder('dw')
            ->delete(DailyWeather::class, 'dw')
            ->where('dw.city = :city')
            ->andWhere('dw.date < :thresholdDate')
            ->setParameter('city', $city)
            ->setParameter('thresholdDate', $thresholdDate)
            ->getQuery()
            ->execute();
    }
}
