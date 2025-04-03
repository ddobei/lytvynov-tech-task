<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\WeatherService;


final class WeatherController extends AbstractController
{
    public function __construct(private WeatherService $weatherService)
    {
    }

    #[Route('/', name: 'app_index')]
    public function weather(): Response
    {
        $weatherData = $this->weatherService->getWeatherData("London");

        if (isset($weatherData['error'])) {
            return $this->render('weather/error.html.twig', [
                'error' => $weatherData['error'],
            ]);
        }

        return $this->render('weather/index.html.twig', [
            'weather' => $weatherData,
        ]);
    }
}
