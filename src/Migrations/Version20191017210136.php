<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191017210136 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE video_request (id UUID NOT NULL, tweet_url VARCHAR(255) NOT NULL, requested_by VARCHAR(255) NOT NULL, processed BOOLEAN NOT NULL, reply_url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DE7A6632F86F2612 ON video_request (tweet_url)');
        $this->addSql('COMMENT ON COLUMN video_request.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE api_user (id UUID NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_token VARCHAR(255) DEFAULT NULL, plain_password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BAF85E0677 ON api_user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BA7BA2F5EB ON api_user (api_token)');
        $this->addSql('COMMENT ON COLUMN api_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE video (id UUID NOT NULL, request_id UUID NOT NULL, filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, thumbnail_url VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2C427EB8A5 ON video (request_id)');
        $this->addSql('COMMENT ON COLUMN video.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN video.request_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C427EB8A5 FOREIGN KEY (request_id) REFERENCES video_request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE video DROP CONSTRAINT FK_7CC7DA2C427EB8A5');
        $this->addSql('DROP TABLE video_request');
        $this->addSql('DROP TABLE api_user');
        $this->addSql('DROP TABLE video');
    }
}
