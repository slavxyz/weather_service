<?php

namespace App\DataFixtures;

use App\V1\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cities = [
            ['Madrid', 40.416500, -3.702560],
            ['Berlin', 52.524370, 13.410530],
            ['Plovdiv', 42.150000, 24.750000],
            ['Munich', 48.137430, 11.575490],
            ['Stockholm', 59.329380, 18.068710],
            ['Lisboa', -20.120560, 33.765830],
            ['Budapest', 47.498350, 19.040450],
            ['Belgrad', 45.216670, 14.733330],
            ['Budva', 42.286390, 18.840000],
            ['Varna', 43.216670, 27.916670],
            ['Sofia', 42.697510, 23.324150],
            ['Vidin', 43.991590, 22.882360],
            ['London', 51.508530, -0.125740],
            ['Bamberg', 33.297100, -81.034820],
            ['Burg', 52.271520, 11.854930],
        ];

        foreach ($cities as $index => $data) {
            $city = new City($data[0], $data[1], $data[2]);

            $this->addReference('city_' . ($index + 1), $city);

            $manager->persist($city);
        }

        $manager->flush();
    }
}
