<?php

namespace App\DataFixtures;

use App\V1\Entity\DailyWeather;
use App\V1\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTime;

class DailyWeatherFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $today = new DateTime();

        for ($i = 0; $i < 10; $i++) {
            $day = (clone $today)->modify("-$i days");

            for ($cityId = 1; $cityId < 16; $cityId++) {
                $city = $this->getReference('city_' . $cityId, City::class);

                $temperature = match ($cityId) {
                    6, 14 => $faker->numberBetween(25, 35),
                    1, 3, 9, 10, 11, 13 => $faker->numberBetween(0, 16),
                    default => $faker->numberBetween(-5, 5),
                };

                $weather = new DailyWeather($city, clone $day, $temperature);

                $manager->persist($weather);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
