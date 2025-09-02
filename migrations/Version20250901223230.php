<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901223230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot ADD full_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE slot ADD street VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE slot ADD city VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE slot ADD state VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE slot ADD zip_code VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE slot ADD country VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE slot DROP full_address');
        $this->addSql('ALTER TABLE slot DROP street');
        $this->addSql('ALTER TABLE slot DROP city');
        $this->addSql('ALTER TABLE slot DROP state');
        $this->addSql('ALTER TABLE slot DROP zip_code');
        $this->addSql('ALTER TABLE slot DROP country');
    }
}
