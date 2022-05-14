<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510080717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, admin VARCHAR(255) NOT NULL, balance INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL)');
        $this->addSql("INSERT INTO users (username, firstname, lastname, password, email, admin, balance) VALUES (admin, admin, admin, $2y$10$MqvxbAdiC1VKJeksRmhwcOLmDYaZsfbxhFIJ899TI5k8Y0VeDWA0y, admin, admin, 10000)");
        $this->addSql("INSERT INTO users (username, firstname, lastname, password, email, admin, balance) VALUES (doe, doe, doe, $2y$10$9QH8AWK6qYrkjciAvsVYCeMTAunq.M4qqkU0QB3KIEpaQmDg.tUfu, doe, user, 1000)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users');
    }
}
