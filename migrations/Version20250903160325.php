<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903160325 extends AbstractMigration
{
    const DEFAULT_ADDRESS = '1 rue du talisman 33000 Bordeaux';

    public function getDescription(): string
    {
        return 'Create preset data for Paris campus';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'insert into campus (id, uid, name) values (nextval(\'campus_id_seq\'), ?, ?)',
            [Uuid::v4()->toRfc4122(), 'Paris']
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Mercure', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Venus', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Terre', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Mars', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Jupiter', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Saturne', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Paris', 'Uranus', self::DEFAULT_ADDRESS, 'talisman', 'Bordeaux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into unavailability (slot_id, uid, type, value) VALUES ((select id from slot where name = ?), ?, ?, ?)',
            ['Uranus', Uuid::v4()->toRfc4122(), 'day', '5']
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
