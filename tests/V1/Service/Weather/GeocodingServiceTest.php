<?php

namespace App\Tests\V1\Service\Weather;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpClient\Exception\TransportException;

use App\V1\Service\Weather\GeocodingService;
use App\V1\Exceptions\Service\Weather\CityNotSupportedException;

class GeocodingServiceTest extends TestCase
{
    public function testGetCoordinatesSuccess(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response   = $this->createMock(ResponseInterface::class);

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://api.test/search',
                [
                    'query' => [
                        'name'  => 'Berlin',
                        'count' => 1,
                    ],
                ]
            )
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'results' => [
                    [
                        'latitude'  => 52.52,
                        'longitude' => 13.405,
                    ],
                ],
            ]);

        $service = new GeocodingService(
            $httpClient,
            'https://api.test/'
        );

        $result = $service->getCoordinates('Berlin');

        $this->assertSame([
            'lat' => 52.52,
            'lon' => 13.405,
        ], $result);
    }
}
