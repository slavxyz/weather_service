<?php

namespace App\V1\Service\Weather; 

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

use App\V1\Exceptions\Service\Weather\CityNotSupportedException;
use App\V1\Interfaces\Weather\GeocodingInterface;

class GeocodingService implements GeocodingInterface
{

    public function __construct(
        private HttpClientInterface $client,
        private string $baseGeocodingUrl
    ) {}

    /**
     * Get latitude and longtitute coordinates of city  
     *
     * @param string $city
     * @return array
     */
    public function getCoordinates(string $city): array
    {
        $url = rtrim($this->baseGeocodingUrl, '/') . '/search';

        try {
            $response = $this->client->request('GET', $url, [
                'query' => [
                    'name'  => $city,
                    'count' => 1,
                ],
            ]);

            $data = $response->toArray();

        } catch (
            TransportExceptionInterface |
            ClientExceptionInterface |
            ServerExceptionInterface | 
            RedirectionExceptionInterface $e
        ) {
            throw new CityNotSupportedException("Unable to fetch coordinates for city $city");
        }

        if (!isset($data['results'][0]) ||
            !isset($data['results'][0]['latitude'], $data['results'][0]['longitude'])
        ) {
            throw new CityNotSupportedException("City not found {$city}");
        }

        return [
            'lat' => (float) $data['results'][0]['latitude'],
            'lon' => (float) $data['results'][0]['longitude'],
        ];
    }
}
