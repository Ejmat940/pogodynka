<?php

namespace App\Command;

use App\Repository\LocalizationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\WeatherRepository;

#[AsCommand(
    name: 'weather:location',
    description: 'Displays the weather forecast for a location',
)]

// Użyto Copilota przy tworzeniu komendy
class WeatherLocationCommand extends Command
{
    public function __construct(
        private readonly LocalizationRepository $localizationRepository,
        private readonly WeatherRepository $weatherRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Location ID');
    }

    // Funkcja zmodyfikowana przy użyciu Copilot
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $location = $this->localizationRepository->find((int) $input->getArgument('id'));

        if ($location === null) {
            $io->error('Location not found.');

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
