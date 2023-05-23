<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522145930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commercial ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commercial ADD CONSTRAINT FK_7653F3AE79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7653F3AE79F37AE5 ON commercial (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commercial DROP FOREIGN KEY FK_7653F3AE79F37AE5');
        $this->addSql('DROP INDEX IDX_7653F3AE79F37AE5 ON commercial');
        $this->addSql('ALTER TABLE commercial DROP id_user_id');
    }
}
