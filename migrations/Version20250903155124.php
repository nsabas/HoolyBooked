<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903155124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE unavailability (id SERIAL NOT NULL, slot_id INT NOT NULL, uid VARCHAR(64) NOT NULL, type VARCHAR(32) NOT NULL, value VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0016D159E5119C ON unavailability (slot_id)');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D159E5119C FOREIGN KEY (slot_id) REFERENCES slot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D159E5119C');
        $this->addSql('DROP TABLE unavailability');
    }
}
