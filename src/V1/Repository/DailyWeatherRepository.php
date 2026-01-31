<?php

namespace App\V1\Repository;

use App\V1\Entity\DailyWeather;
use App\V1\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateTimeImmutable;

class DailyWeatherRepository extends ServiceEntityRepository
{

    private $em;

    const HISTORY_LIMIT_DAYS = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyWeather::class);

        $this->em = $this->getEntityManager();
    }

    /**
     * Return last N days temperatures
     *
     * @param City $city
     * @param int $limit
     * @return float
     */
    public function getLastDaysAverage(City $city, int $limit = self::HISTORY_LIMIT_DAYS): float
    {
        return (float) $this->createQueryBuilder('d')
            ->select('AVG(d.temperature)')
            ->where('d.city = :city')
            ->setParameter('city', $city)
            ->orderBy('d.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Stores data for current city
     *
     * @param City $city
     * @param float $temperature
     * @return void
     */
    public function saveToday(City $city, float $temperature): void
    {
        $today = new DateTime('today');

        $record = $this->findOneBy([
            'city' => $city,
            'date' => $today
        ]);

        if ($record) {
            return; 
        }

        $this->em->persist(
            new DailyWeather($city, $today, $temperature)
        );

        $this->em->flush();

        $this->trimHistory($city);
    }

    /**
     * Deletes outdated daily weather records for a specific city.
     *
     * @param City $city
     * @return void
     */
    private function trimHistory(City $city): void
    {
        $thresholdDate = new DateTimeImmutable(
            sprintf('-%d days', self::HISTORY_LIMIT_DAYS)
        );

        $this->createQueryBuilder('d')
            ->delete(DailyWeather::class, 'd')
            ->where('d.city = :city')
            ->andWhere('d.date < :thresholdDate')
            ->setParameter('city', $city)
            ->setParameter('thresholdDate', $thresholdDate)
            ->getQuery()
            ->execute();
    }
}
