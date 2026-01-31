<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260125000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create cities and daily_weather tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE cities (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(100) NOT NULL,
                latitude DECIMAL(9,6) NOT NULL,
                longitude DECIMAL(9,6) NOT NULL,
                UNIQUE INDEX uniq_city_name (name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE daily_weather (
                id INT AUTO_INCREMENT NOT NULL,
                city_id INT NOT NULL,
                date DATE NOT NULL,
                temperature DECIMAL(5,2) NOT NULL,
                created_at DATETIME NOT NULL,
                UNIQUE INDEX uniq_city_date (city_id, date),
                INDEX idx_city_date (city_id, date),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            ALTER TABLE daily_weather
            ADD CONSTRAINT FK_DAILY_WEATHER_CITY
            FOREIGN KEY (city_id) REFERENCES cities (id)
            ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE daily_weather DROP FOREIGN KEY FK_DAILY_WEATHER_CITY');
        $this->addSql('DROP TABLE daily_weather');
        $this->addSql('DROP TABLE cities');
    }
}
