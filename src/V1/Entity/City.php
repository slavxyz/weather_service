<?php

namespace App\V1\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\V1\Repository\CityRepository;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\Table(name: 'cities')]
#[ORM\UniqueConstraint(name: 'uniq_city_name', columns: ['name'])]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 9, scale: 6)]
    private float $latitude;

    #[ORM\Column(type: 'decimal', precision: 9, scale: 6)]
    private float $longitude;

    public function __construct(string $name, float $latitude, float $longitude)
    {
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getLatitude(): float { return $this->latitude; }
    public function getLongitude(): float { return $this->longitude; }
}
