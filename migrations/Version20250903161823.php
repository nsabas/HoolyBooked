<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903161823 extends AbstractMigration
{
    const DEFAULT_ADDRESS = '20 rue du Lac 69000 Lyon';

    public function getDescription(): string
    {
        return 'Create preset data for Lyon campus';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'insert into campus (id, uid, name) values (nextval(\'campus_id_seq\'), ?, ?)',
            [Uuid::v4()->toRfc4122(), 'Lyon']
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Lyon', 'Londres', self::DEFAULT_ADDRESS, 'talisman', 'Bordeux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Lyon', 'Sydney', self::DEFAULT_ADDRESS, 'talisman', 'Bordeux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Lyon', 'New York', self::DEFAULT_ADDRESS, 'talisman', 'Bordeux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Lyon', 'Zurich', self::DEFAULT_ADDRESS, 'talisman', 'Bordeux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into slot (campus_id, name, full_address, street, city, state, zip_code, country, uid) 
                    values (
                            (select id from campus where name = ?), ?, ?, ?, ?, ?, ?, ?, ?
                    )',
            ['Lyon', 'Abu Dhabi', self::DEFAULT_ADDRESS, 'talisman', 'Bordeux', 'Gironde', '33000', 'France', Uuid::v4()->toRfc4122()]
        );

        $this->addSql(
            'insert into unavailability (slot_id, uid, type, value) VALUES ((select id from slot where name = ?), ?, ?, ?)',
            ['Zurich', Uuid::v4()->toRfc4122(), 'day', '1']
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
