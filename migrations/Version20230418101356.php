<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230418101356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avance (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, ref_recu VARCHAR(20) NOT NULL, INDEX IDX_B623B3CC85542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_offre_id INT NOT NULL, id_commercial_id INT DEFAULT NULL, id_user_id INT NOT NULL, date_reservation DATETIME NOT NULL, num_voyageurs INT NOT NULL, remarque VARCHAR(255) DEFAULT NULL, mnt_commission DOUBLE PRECISION DEFAULT NULL, avance_commission DOUBLE PRECISION DEFAULT NULL, date_avance_commission DOUBLE PRECISION DEFAULT NULL, INDEX IDX_42C849551C13BCCF (id_offre_id), INDEX IDX_42C84955C67CD679 (id_commercial_id), INDEX IDX_42C8495579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyageur (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, cin VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, num_passport VARCHAR(100) DEFAULT NULL, date_fin_passport DATE DEFAULT NULL, date_naissance DATE DEFAULT NULL, INDEX IDX_FE32225485542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avance ADD CONSTRAINT FK_B623B3CC85542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551C13BCCF FOREIGN KEY (id_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C67CD679 FOREIGN KEY (id_commercial_id) REFERENCES commercial (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE voyageur ADD CONSTRAINT FK_FE32225485542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F79F37AE5');
        $this->addSql('DROP INDEX IDX_AF86866F79F37AE5 ON offre');
        $this->addSql('ALTER TABLE offre CHANGE id_user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF86866FA76ED395 ON offre (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avance DROP FOREIGN KEY FK_B623B3CC85542AE1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551C13BCCF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C67CD679');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE voyageur DROP FOREIGN KEY FK_FE32225485542AE1');
        $this->addSql('DROP TABLE avance');
        $this->addSql('DROP TABLE commercial');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE voyageur');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA76ED395');
        $this->addSql('DROP INDEX IDX_AF86866FA76ED395 ON offre');
        $this->addSql('ALTER TABLE offre CHANGE user_id id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F79F37AE5 ON offre (id_user_id)');
    }
}
