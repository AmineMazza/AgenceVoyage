<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230701214529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, agence VARCHAR(100) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone_mobile VARCHAR(20) NOT NULL, telephone_fixe VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, abonnement VARCHAR(20) NOT NULL, bstatus TINYINT(1) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_268B9C9D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avance (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, ref_recu VARCHAR(20) NOT NULL, INDEX IDX_B623B3CC85542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, cin VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_7653F3AEABE530DA (cin), INDEX IDX_7653F3AE79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commercial_agent (commercial_id INT NOT NULL, agent_id INT NOT NULL, INDEX IDX_9F59EBC7854071C (commercial_id), INDEX IDX_9F59EBC3414710B (agent_id), PRIMARY KEY(commercial_id, agent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, pays VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, id_offre_id INT NOT NULL, lieu VARCHAR(59) NOT NULL, etoile SMALLINT NOT NULL, distance DOUBLE PRECISION NOT NULL, nombre_nuits BIGINT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3535ED91C13BCCF (id_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, id_offre_id INT DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, email VARCHAR(100) NOT NULL, message VARCHAR(255) NOT NULL, date_envoi DATETIME NOT NULL, telephone VARCHAR(40) DEFAULT NULL, bstatus TINYINT(1) DEFAULT NULL, INDEX IDX_B6BD307F1C13BCCF (id_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletters (id INT AUTO_INCREMENT NOT NULL, categories_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_sent TINYINT(1) NOT NULL, INDEX IDX_8ECF000CA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_destination_id INT NOT NULL, titre VARCHAR(100) NOT NULL, image VARCHAR(255) DEFAULT NULL, date_depart DATETIME NOT NULL, date_retour DATETIME NOT NULL, bhebergement TINYINT(1) DEFAULT NULL, bvisa TINYINT(1) DEFAULT NULL, bdemi_pension TINYINT(1) DEFAULT NULL, bpension_complete TINYINT(1) DEFAULT NULL, bvisite_medine TINYINT(1) DEFAULT NULL, prix_un DOUBLE PRECISION DEFAULT NULL, prix_double DOUBLE PRECISION DEFAULT NULL, prix_triple DOUBLE PRECISION DEFAULT NULL, prix_quad DOUBLE PRECISION DEFAULT NULL, prix_quint DOUBLE PRECISION DEFAULT NULL, bcoup_coeur TINYINT(1) DEFAULT NULL, bpubier TINYINT(1) DEFAULT NULL, date_publication DATE DEFAULT NULL, date_fin_publication DATE DEFAULT NULL, bpassport TINYINT(1) DEFAULT NULL, bphotos TINYINT(1) DEFAULT NULL, bpass_vacinial TINYINT(1) DEFAULT NULL, detail_voyage VARCHAR(255) DEFAULT NULL, detail_vols VARCHAR(255) DEFAULT NULL, prix_demi_pension DOUBLE PRECISION DEFAULT NULL, prix_complete_pension DOUBLE PRECISION DEFAULT NULL, detail_demi_pension VARCHAR(255) DEFAULT NULL, detail_complete_pension VARCHAR(255) DEFAULT NULL, INDEX IDX_AF86866F79F37AE5 (id_user_id), INDEX IDX_AF86866FBC0ADC46 (id_destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_offre_id INT NOT NULL, id_commercial_id INT DEFAULT NULL, id_user_id INT NOT NULL, date_reservation DATETIME NOT NULL, num_voyageurs INT NOT NULL, remarque VARCHAR(255) DEFAULT NULL, mnt_commission DOUBLE PRECISION DEFAULT NULL, avance_commission DOUBLE PRECISION DEFAULT NULL, date_avance_commission DATETIME DEFAULT NULL, montant_total DOUBLE PRECISION NOT NULL, INDEX IDX_42C849551C13BCCF (id_offre_id), INDEX IDX_42C84955C67CD679 (id_commercial_id), INDEX IDX_42C8495579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_rgpd TINYINT(1) NOT NULL, validation_token VARCHAR(255) NOT NULL, is_valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_categories (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_ED98E9FC67B3B43D (users_id), INDEX IDX_ED98E9FCA21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyageur (id INT AUTO_INCREMENT NOT NULL, id_reservation_id INT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, cin VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, num_passport VARCHAR(100) DEFAULT NULL, date_fin_passport DATE DEFAULT NULL, date_naissance DATE DEFAULT NULL, pension VARCHAR(255) DEFAULT NULL, chambre VARCHAR(255) DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_FE32225485542AE1 (id_reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avance ADD CONSTRAINT FK_B623B3CC85542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE commercial ADD CONSTRAINT FK_7653F3AE79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commercial_agent ADD CONSTRAINT FK_9F59EBC7854071C FOREIGN KEY (commercial_id) REFERENCES commercial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commercial_agent ADD CONSTRAINT FK_9F59EBC3414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED91C13BCCF FOREIGN KEY (id_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1C13BCCF FOREIGN KEY (id_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE newsletters ADD CONSTRAINT FK_8ECF000CA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FBC0ADC46 FOREIGN KEY (id_destination_id) REFERENCES destination (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551C13BCCF FOREIGN KEY (id_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C67CD679 FOREIGN KEY (id_commercial_id) REFERENCES commercial (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FC67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FCA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voyageur ADD CONSTRAINT FK_FE32225485542AE1 FOREIGN KEY (id_reservation_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D79F37AE5');
        $this->addSql('ALTER TABLE avance DROP FOREIGN KEY FK_B623B3CC85542AE1');
        $this->addSql('ALTER TABLE commercial DROP FOREIGN KEY FK_7653F3AE79F37AE5');
        $this->addSql('ALTER TABLE commercial_agent DROP FOREIGN KEY FK_9F59EBC7854071C');
        $this->addSql('ALTER TABLE commercial_agent DROP FOREIGN KEY FK_9F59EBC3414710B');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED91C13BCCF');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1C13BCCF');
        $this->addSql('ALTER TABLE newsletters DROP FOREIGN KEY FK_8ECF000CA21214B7');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F79F37AE5');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FBC0ADC46');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551C13BCCF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C67CD679');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FC67B3B43D');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FCA21214B7');
        $this->addSql('ALTER TABLE voyageur DROP FOREIGN KEY FK_FE32225485542AE1');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE avance');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE commercial');
        $this->addSql('DROP TABLE commercial_agent');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE newsletters');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_categories');
        $this->addSql('DROP TABLE voyageur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
