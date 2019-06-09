<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190609203835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adjacency_list CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bind_contained CHANGE container_id container_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE fields_example CHANGE created_at created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, CHANGE last_modified last_modified DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE history_upload_image CHANGE created_at created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE named_timestampable CHANGE created_at created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, CHANGE last_modified last_modified DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE system_user ADD roles JSON NOT NULL, CHANGE username username VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE user_files CHANGE created_at created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, CHANGE last_modified last_modified DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adjacency_list CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bind_contained CHANGE container_id container_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE fields_example CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\', CHANGE last_modified last_modified DATETIME DEFAULT \'current_timestamp()\'');
        $this->addSql('ALTER TABLE history_upload_image CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\'');
        $this->addSql('ALTER TABLE named_timestampable CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\', CHANGE last_modified last_modified DATETIME DEFAULT \'current_timestamp()\'');
        $this->addSql('ALTER TABLE system_user DROP roles, CHANGE username username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_files CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\', CHANGE last_modified last_modified DATETIME DEFAULT \'current_timestamp()\'');
    }
}
