<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeatherRepository::class)]
class Weather
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $Date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $Temperature = null;

    #[ORM\Column(length: 255)]
    private ?string $Cloud_cover = null;

    #[ORM\Column]
    private ?int $Humidity = null;

    #[ORM\Column]
    private ?int $Wind_strength = null;

    #[ORM\ManyToOne(inversedBy: 'weather')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Localization $Localization = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->Date;
    }

    public function setDate(\DateTime $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->Temperature;
    }

    public function setTemperature(string $Temperature): static
    {
        $this->Temperature = $Temperature;

        return $this;
    }

    public function getCloudCover(): ?string
    {
        return $this->Cloud_cover;
    }

    public function setCloudCover(string $Cloud_cover): static
    {
        $this->Cloud_cover = $Cloud_cover;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->Humidity;
    }

    public function setHumidity(int $Humidity): static
    {
        $this->Humidity = $Humidity;

        return $this;
    }

    public function getWindStrength(): ?int
    {
        return $this->Wind_strength;
    }

    public function setWindStrength(int $Wind_strength): static
    {
        $this->Wind_strength = $Wind_strength;

        return $this;
    }

    public function getLocalization(): ?Localization
    {
        return $this->Localization;
    }

    public function setLocalization(?Localization $Localization): static
    {
        $this->Localization = $Localization;

        return $this;
    }
}
