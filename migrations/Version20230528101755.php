<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528101755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP baller_retour');
        $this->addSql('ALTER TABLE voyageur ADD pension VARCHAR(255) DEFAULT NULL, ADD chambre VARCHAR(255) DEFAULT NULL, ADD montant DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD baller_retour TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE voyageur DROP pension, DROP chambre, DROP montant');
    }
}
