<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209004358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add image column back to evenement table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE evenement ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE evenement DROP image');
    }
}
