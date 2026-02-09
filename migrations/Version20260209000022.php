<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209000022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date and fundraising fields to evenement table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement ADD date_debut DATETIME DEFAULT NULL, ADD date_fin DATETIME DEFAULT NULL, ADD objectif_financement DOUBLE PRECISION DEFAULT NULL, ADD montant_collecte DOUBLE PRECISION DEFAULT 0, ADD image VARCHAR(500) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP COLUMN date_debut, DROP COLUMN date_fin, DROP COLUMN objectif_financement, DROP COLUMN montant_collecte, DROP COLUMN image');
    }
}
