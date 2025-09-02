<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901220808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE slot (id SERIAL NOT NULL, campus_id INT NOT NULL, name VARCHAR(180) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC0E2067AF5D55E1 ON slot (campus_id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE campus DROP nb_slots');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE slot DROP CONSTRAINT FK_AC0E2067AF5D55E1');
        $this->addSql('DROP TABLE slot');
        $this->addSql('ALTER TABLE campus ADD nb_slots INT NOT NULL');
    }
}
