<?php

namespace App\V1\Controller;

use App\V1\Service\Weather\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends AbstractController
{
    #[Route('/api/v1/cities/{city}/weather', methods: ['GET'])]
    public function index(string $city, WeatherService $weatherService): Response
    {
        try {
            $data = $weatherService->getWeatherData($city);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 400);
        }
        
        return new JsonResponse([
            'city'        => ucfirst($city),
            'temperature' => $data['temp'],
            'trend'       => $data['trend'],
            'day'         => $data['day'],
            'date'        => $data['date']
        ]);
    }
}
