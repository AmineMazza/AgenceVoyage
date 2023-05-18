<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517105728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD prix_un DOUBLE PRECISION DEFAULT NULL, ADD prix_double DOUBLE PRECISION DEFAULT NULL, ADD prix_triple DOUBLE PRECISION DEFAULT NULL, ADD prix_quad DOUBLE PRECISION DEFAULT NULL, ADD prix_quint DOUBLE PRECISION DEFAULT NULL, ADD prix_demi_pension DOUBLE PRECISION DEFAULT NULL, ADD prix_complete_pension DOUBLE PRECISION DEFAULT NULL, ADD detail_demi_pension VARCHAR(255) DEFAULT NULL, ADD detail_complete_pension VARCHAR(255) DEFAULT NULL, DROP prix_chambre, DROP prix_chambre_double, DROP prix_chambre_triple, DROP prix_chambre_quad, DROP prix_chambre_quint, DROP prix');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD prix_chambre DOUBLE PRECISION DEFAULT NULL, ADD prix_chambre_double DOUBLE PRECISION DEFAULT NULL, ADD prix_chambre_triple DOUBLE PRECISION DEFAULT NULL, ADD prix_chambre_quad DOUBLE PRECISION DEFAULT NULL, ADD prix_chambre_quint DOUBLE PRECISION DEFAULT NULL, ADD prix DOUBLE PRECISION NOT NULL, DROP prix_un, DROP prix_double, DROP prix_triple, DROP prix_quad, DROP prix_quint, DROP prix_demi_pension, DROP prix_complete_pension, DROP detail_demi_pension, DROP detail_complete_pension');
    }
}
