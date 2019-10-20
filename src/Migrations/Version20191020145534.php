<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020145534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE video_thumbnail (id UUID NOT NULL, filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN video_thumbnail.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE video ADD thumbnail_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE video DROP thumbnail');
        $this->addSql('COMMENT ON COLUMN video.thumbnail_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES video_thumbnail (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2CFDFF2E92 ON video (thumbnail_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE video DROP CONSTRAINT FK_7CC7DA2CFDFF2E92');
        $this->addSql('DROP TABLE video_thumbnail');
        $this->addSql('DROP INDEX UNIQ_7CC7DA2CFDFF2E92');
        $this->addSql('ALTER TABLE video ADD thumbnail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE video DROP thumbnail_id');
    }
}
