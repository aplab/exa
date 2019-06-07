<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607190405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adjacency_list (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, sort_order BIGINT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX parent_id (parent_id), INDEX sort_order (sort_order), INDEX order_id (sort_order, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bind_contained (id BIGINT AUTO_INCREMENT NOT NULL, container_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_89602878BC21F742 (container_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bind_container (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fields_example (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, flag TINYINT(1) NOT NULL, textarea VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, image2 VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, last_modified DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history_upload_image (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, width INT UNSIGNED NOT NULL, height INT UNSIGNED NOT NULL, favorites TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, INDEX path (path), INDEX favorites (favorites), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE named_timestampable (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, last_modified DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE my_sample_entity (id BIGINT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE system_user (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9C5F65BFF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_files (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, content_type VARCHAR(255) NOT NULL, created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP, last_modified DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adjacency_list ADD CONSTRAINT FK_FDE5D6BF727ACA70 FOREIGN KEY (parent_id) REFERENCES adjacency_list (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE bind_contained ADD CONSTRAINT FK_89602878BC21F742 FOREIGN KEY (container_id) REFERENCES bind_container (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adjacency_list DROP FOREIGN KEY FK_FDE5D6BF727ACA70');
        $this->addSql('ALTER TABLE bind_contained DROP FOREIGN KEY FK_89602878BC21F742');
        $this->addSql('DROP TABLE adjacency_list');
        $this->addSql('DROP TABLE bind_contained');
        $this->addSql('DROP TABLE bind_container');
        $this->addSql('DROP TABLE fields_example');
        $this->addSql('DROP TABLE history_upload_image');
        $this->addSql('DROP TABLE named_timestampable');
        $this->addSql('DROP TABLE my_sample_entity');
        $this->addSql('DROP TABLE system_user');
        $this->addSql('DROP TABLE user_files');
    }
}
