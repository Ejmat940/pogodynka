<?php

namespace App\Controller;

use App\Repository\LocalizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{id}', name: 'app_weather', requirements: ['id' => '\d+'])]
    public function city(int $id, LocalizationRepository $localizationRepository): Response
    {
        $location = $localizationRepository->find($id);

        if ($location === null) {
            throw $this->createNotFoundException('Nie znaleziono lokalizacji.');
        }

        return $this->render('weather/city.html.twig', [
            'controller_name' => 'WeatherController',
            'location' => $location,
            'forecast' => $localizationRepository->findByLocation($location),
        ]);
    }
}
