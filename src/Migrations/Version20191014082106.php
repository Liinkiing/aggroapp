<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014082106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE video_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE video_request (id INT NOT NULL, tweet_url VARCHAR(255) NOT NULL, requested_by VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER INDEX uniq_8d93d649f85e0677 RENAME TO UNIQ_AC64A0BAF85E0677');
        $this->addSql('ALTER INDEX uniq_8d93d6497ba2f5eb RENAME TO UNIQ_AC64A0BA7BA2F5EB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE video_request_id_seq CASCADE');
        $this->addSql('DROP TABLE video_request');
        $this->addSql('ALTER INDEX uniq_ac64a0ba7ba2f5eb RENAME TO uniq_8d93d6497ba2f5eb');
        $this->addSql('ALTER INDEX uniq_ac64a0baf85e0677 RENAME TO uniq_8d93d649f85e0677');
    }
}
