<?php

namespace App\V1\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\V1\Repository\DailyWeatherRepository;
use DateTimeImmutable;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: DailyWeatherRepository::class)]
#[ORM\Table(name: 'daily_weather')]
#[ORM\UniqueConstraint(name: 'uniq_city_date', columns: ['city_id', 'date'])]
#[ORM\Index(name: 'idx_city_date', columns: ['city_id', 'date'])]
class DailyWeather
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: City::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private City $city;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $date;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private float $temperature;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(City $city, DateTimeInterface $date, float $temperature)
    {
        $this->city = $city;
        $this->date = $date;
        $this->temperature = $temperature;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
