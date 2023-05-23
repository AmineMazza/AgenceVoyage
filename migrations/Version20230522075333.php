<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522075333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commercial_agent (commercial_id INT NOT NULL, agent_id INT NOT NULL, INDEX IDX_9F59EBC7854071C (commercial_id), INDEX IDX_9F59EBC3414710B (agent_id), PRIMARY KEY(commercial_id, agent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commercial_agent ADD CONSTRAINT FK_9F59EBC7854071C FOREIGN KEY (commercial_id) REFERENCES commercial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commercial_agent ADD CONSTRAINT FK_9F59EBC3414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commercial ADD cin VARCHAR(15) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7653F3AEABE530DA ON commercial (cin)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commercial_agent DROP FOREIGN KEY FK_9F59EBC7854071C');
        $this->addSql('ALTER TABLE commercial_agent DROP FOREIGN KEY FK_9F59EBC3414710B');
        $this->addSql('DROP TABLE commercial_agent');
        $this->addSql('DROP INDEX UNIQ_7653F3AEABE530DA ON commercial');
        $this->addSql('ALTER TABLE commercial DROP cin');
    }
}
