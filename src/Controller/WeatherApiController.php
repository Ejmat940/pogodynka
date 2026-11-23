<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Weather;
use App\Service\WeatherUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

// Zmodyfikowane przy pomocy Copilot
final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        if (!in_array($format, ['json', 'csv'], true)) {
            throw new BadRequestHttpException('Format must be either "json" or "csv".');
        }

        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);
        $formattedMeasurements = array_map(fn (Weather $measurement) => [
            'date' => $measurement->getDate()?->format('Y-m-d'),
            'celsius' => $measurement->getCelsius(),
            'fahrenheit' => $measurement->getFahrenheit(),
        ], $measurements);

        // Zrobione z pomocą Copilota
        if ($twig) {
            $response = $this->render(sprintf('weather_api/index.%s.twig', $format), [
                'city' => $city,
                'country' => $country,
                'measurements' => $formattedMeasurements,
            ]);
            $response->headers->set(
                'Content-Type',
                $format === 'json' ? 'application/json; charset=UTF-8' : 'text/plain; charset=UTF-8',
            );
            if ($format === 'csv') {
                $response->headers->set('Content-Disposition', 'inline');
            }

            return $response;
        }

        if ($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => $formattedMeasurements,
            ]);
        }

        // Zmodyfikowane przy pomocy Copilota
        $csvRows = [['city', 'country', 'date', 'celsius', 'fahrenheit']];
        foreach ($formattedMeasurements as $measurement) {
            $csvRows[] = [
                $city,
                $country,
                $measurement['date'] ?? '',
                $measurement['celsius'] ?? '',
                $measurement['fahrenheit'] ?? '',
            ];
        }

        $csv = implode("\r\n", array_map(
            static fn (array $row) => implode(',', array_map(
                static fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"',
                $row,
            )),
            $csvRows,
        ));

        return new Response($csv, Response::HTTP_OK, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'inline',
        ]);
    }
}
