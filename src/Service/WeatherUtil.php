<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Localization;
use App\Entity\Weather;
use App\Repository\LocalizationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocalizationRepository $localizationRepository,
    ) {
    }

    /** @return Weather[] */
    public function getWeatherForLocation(Localization $location): array
    {
        return $this->localizationRepository->findByLocation($location);
    }

    /** @return Weather[] */
    // Użyto Copilota przy dodawaniu metoDY
    public function getWeatherForCountryAndCity(string $countryCode, string $cityName): array
    {
        $location = $this->localizationRepository->findOneBy([
            'Country' => $countryCode,
            'City' => $cityName,
        ]);

        if (!$location instanceof Localization) {
            throw new \InvalidArgumentException(sprintf(
                'Nie znaleziono lokalizacji kraju "%s" i miasta "%s".',
                $countryCode,
                $cityName,
            ));
        }

        return $this->getWeatherForLocation($location);
    }
}
