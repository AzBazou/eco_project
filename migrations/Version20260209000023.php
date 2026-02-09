<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209000023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop unnecessary columns from evenement table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP COLUMN domaine_action, DROP COLUMN objectif_financement, DROP COLUMN montant_collecte, DROP COLUMN image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement ADD domaine_action VARCHAR(100) NOT NULL, ADD objectif_financement DOUBLE PRECISION DEFAULT NULL, ADD montant_collecte DOUBLE PRECISION DEFAULT 0, ADD image VARCHAR(500) DEFAULT NULL');
    }
}
