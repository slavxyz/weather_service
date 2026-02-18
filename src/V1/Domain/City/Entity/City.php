<?php

namespace App\V1\Domain\City\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\V1\Infrastructure\City\CityRepository;
use App\V1\Domain\City\Coordinates;
use App\V1\Domain\City\Exception\CityNotSupportedException;

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

    private function __construct(string $name, Coordinates $coordinates)
    {
        $name = trim($name);

        if ($name === '') {
            throw new CityNotSupportedException('City name cannot be empty');
        }

        $this->name = strtolower($name);
        $this->latitude  = $coordinates->latitude();
        $this->longitude = $coordinates->longitude();
    }

    public static function create(string $name, Coordinates $coordinates): self
    {
        return new self($name, $coordinates);
    }

    public function coordinates(): Coordinates
    {
        return new Coordinates($this->latitude, $this->longitude);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
