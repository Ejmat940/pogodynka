<?php

namespace App\Command;

use App\Repository\LocalizationRepository;
use App\Repository\WeatherRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location-by-city',
    description: 'Displays the weather forecast by country code and city name',
)]
class WeatherLocationByCityCommand extends Command
{
    public function __construct(
        private readonly LocalizationRepository $localizationRepository,
        private readonly WeatherRepository $weatherRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code')
            ->addArgument('cityName', InputArgument::REQUIRED, 'City name');
    }

    // Komenda zmodyfikowana przy użyciu Copilota
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = (string) $input->getArgument('countryCode');
        $cityName = (string) $input->getArgument('cityName');

        $location = $this->localizationRepository->findOneBy([
            'Country' => $countryCode,
            'City' => $cityName,
        ]);

        if ($location === null) {
            $io->error(sprintf('Location "%s, %s" not found.', $cityName, $countryCode));

            return Command::FAILURE;
        }

        $forecast = $this->weatherRepository->findBy(
            ['Localization' => $location],
            ['Date' => 'ASC'],
        );

        if ($forecast === []) {
            $io->note('No forecast available for this location.');

            return Command::SUCCESS;
        }

        $rows = [];
        foreach ($forecast as $measurement) {
            $rows[] = [
                $measurement->getDate()?->format('Y-m-d') ?? '',
                $measurement->getTemperature() ?? '',
                $measurement->getCloudCover() ?? '',
                $measurement->getHumidity() ?? '',
                $measurement->getWindStrength() ?? '',
            ];
        }

        $io->title(sprintf('Weather forecast for %s, %s', $location->getCity(), $location->getCountry()));
        $io->table(['Date', 'Temperature', 'Cloud cover', 'Humidity', 'Wind strength'], $rows);

        return Command::SUCCESS;
    }
}
