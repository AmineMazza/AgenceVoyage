<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414120618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, agence VARCHAR(100) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone_mobile VARCHAR(20) NOT NULL, telephone_fixe VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, abonnememt VARCHAR(20) NOT NULL, bstatus TINYINT(1) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_268B9C9D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, pays VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, id_offre_id INT NOT NULL, lieu VARCHAR(59) NOT NULL, etoile SMALLINT NOT NULL, distance DOUBLE PRECISION NOT NULL, nombre_nuits BIGINT NOT NULL, INDEX IDX_3535ED91C13BCCF (id_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, id_offre_id INT DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, email VARCHAR(100) NOT NULL, message VARCHAR(255) NOT NULL, date_envoi DATETIME NOT NULL, INDEX IDX_B6BD307F1C13BCCF (id_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_destination_id INT NOT NULL, titre VARCHAR(100) NOT NULL, image VARCHAR(255) DEFAULT NULL, date_depart DATETIME NOT NULL, date_retour DATETIME NOT NULL, ballerr_etour TINYINT(1) NOT NULL, bhebergement TINYINT(1) DEFAULT NULL, bvisa TINYINT(1) DEFAULT NULL, bpetit_dejeuner TINYINT(1) DEFAULT NULL, bdemi_pension TINYINT(1) DEFAULT NULL, bpension_complete TINYINT(1) DEFAULT NULL, bvisite_medine TINYINT(1) DEFAULT NULL, prix_chambre DOUBLE PRECISION DEFAULT NULL, prix_chambre_double DOUBLE PRECISION DEFAULT NULL, prix_chambre_triple DOUBLE PRECISION DEFAULT NULL, prix_chambre_quad DOUBLE PRECISION DEFAULT NULL, prix_chambre_quint DOUBLE PRECISION DEFAULT NULL, bcoup_coeur TINYINT(1) DEFAULT NULL, bpubier TINYINT(1) DEFAULT NULL, date_publication DATE DEFAULT NULL, date_fin_publication DATE DEFAULT NULL, bpassport TINYINT(1) DEFAULT NULL, bphotos TINYINT(1) DEFAULT NULL, bpass_vacinial TINYINT(1) DEFAULT NULL, INDEX IDX_AF86866F79F37AE5 (id_user_id), INDEX IDX_AF86866FBC0ADC46 (id_destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED91C13BCCF FOREIGN KEY (id_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1C13BCCF FOREIGN KEY (id_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FBC0ADC46 FOREIGN KEY (id_destination_id) REFERENCES destination (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D79F37AE5');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED91C13BCCF');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1C13BCCF');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F79F37AE5');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FBC0ADC46');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE offre');
    }
}
