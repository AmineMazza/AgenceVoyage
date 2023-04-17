<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417122238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FBC0ADC46');
        $this->addSql('DROP INDEX IDX_AF86866FBC0ADC46 ON offre');
        $this->addSql('ALTER TABLE offre DROP id_destination_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD id_destination_id INT NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FBC0ADC46 FOREIGN KEY (id_destination_id) REFERENCES destination (id)');
        $this->addSql('CREATE INDEX IDX_AF86866FBC0ADC46 ON offre (id_destination_id)');
    }
}
