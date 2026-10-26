<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261026220144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weather_in (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__weather AS SELECT id, date, temperature, cloud_cover, humidity, wind_strength FROM weather');
        $this->addSql('DROP TABLE weather');
        $this->addSql('CREATE TABLE weather (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, temperature NUMERIC(10, 0) NOT NULL, cloud_cover VARCHAR(255) NOT NULL, humidity INTEGER NOT NULL, wind_strength INTEGER NOT NULL, localization_id INTEGER NOT NULL, CONSTRAINT FK_4CD0D36E6A2856C7 FOREIGN KEY (localization_id) REFERENCES localization (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO weather (id, date, temperature, cloud_cover, humidity, wind_strength) SELECT id, date, temperature, cloud_cover, humidity, wind_strength FROM __temp__weather');
        $this->addSql('DROP TABLE __temp__weather');
        $this->addSql('CREATE INDEX IDX_4CD0D36E6A2856C7 ON weather (localization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE weather_in');
        $this->addSql('CREATE TEMPORARY TABLE __temp__weather AS SELECT id, date, temperature, cloud_cover, humidity, wind_strength FROM weather');
        $this->addSql('DROP TABLE weather');
        $this->addSql('CREATE TABLE weather (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, temperature NUMERIC(10, 0) NOT NULL, cloud_cover VARCHAR(255) NOT NULL, humidity INTEGER NOT NULL, wind_strength INTEGER NOT NULL)');
        $this->addSql('INSERT INTO weather (id, date, temperature, cloud_cover, humidity, wind_strength) SELECT id, date, temperature, cloud_cover, humidity, wind_strength FROM __temp__weather');
        $this->addSql('DROP TABLE __temp__weather');
    }
}
