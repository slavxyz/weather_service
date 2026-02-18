<?php

namespace App\V1\Domain\Weather\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\V1\Infrastructure\Weather\DailyWeatherRepository;
use DateTimeImmutable;
use DateTimeInterface;
use App\V1\Domain\Weather\Temperature;
use App\V1\Domain\City\Entity\City;

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

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $date;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private float $temperature;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(
        City $city,
        DateTimeImmutable $date,
        float $temperature
    ) {
        $this->city = $city;
        $this->date = $date;
        $this->temperature = $temperature;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function record(
        City $city,
        DateTimeInterface $date,
        float $temperature
    ): self {
        return new self(
            $city,
            DateTimeImmutable::createFromInterface($date)->setTime(0, 0),
            $temperature
        );
    }

    public static function recordOrUpdate(?self $existing, City $city, DateTimeImmutable $date, float $temperature): self
    {
        if ($existing) {
            $existing->updateTemperature($temperature);
            return $existing;
        }

        return self::record($city, $date, $temperature);
    }

    public function updateTemperature(float $temperature): void
    {
        $this->temperature = $temperature;
    }

    public function temperature(): Temperature
    {
        return new Temperature($this->temperature);
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function city(): City
    {
        return $this->city;
    }
}
