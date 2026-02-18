<?php

namespace App\V1\Controller;

use App\V1\Application\Weather\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends AbstractController
{
    #[Route('/api/v1/cities/{city}/weather', methods: ['GET'])]
    public function index(?string $city, WeatherService $weatherService): Response
    {
        if (empty($city)) {
           return new Response('City parameter is required.', 400);
        }

        try {
            $data = $weatherService->getWeatherData($city);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 400);
        }

        return new JsonResponse([
            'city'        => ucfirst($city),
            'temperature' => $data['temperature'],
            'trend'       => $data['trend'],
        ]);
    }
}
