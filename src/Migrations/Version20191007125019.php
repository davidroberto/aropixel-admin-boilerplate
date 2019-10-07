<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191007125019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, extension VARCHAR(20) NOT NULL, import LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, attr_title VARCHAR(255) DEFAULT NULL, attr_alt VARCHAR(255) DEFAULT NULL, attr_description VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, extension VARCHAR(20) NOT NULL, import LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_AD8A54A9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, page_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, title LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, original_title LONGTEXT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, static_page VARCHAR(50) DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, root INT DEFAULT NULL, INDEX IDX_7D053A93727ACA70 (parent_id), INDEX IDX_7D053A93C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, status VARCHAR(20) NOT NULL, code VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, excerpt LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, is_page_title_enabled TINYINT(1) NOT NULL, is_page_excerpt_enabled TINYINT(1) NOT NULL, is_page_description_enabled TINYINT(1) NOT NULL, is_page_image_enabled TINYINT(1) NOT NULL, is_page_gallery_enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, publish_at DATETIME DEFAULT NULL, publish_until DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_140AB6203DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_gallery (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, INDEX IDX_BD4B93AFC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_gallery_crop (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, filter VARCHAR(255) NOT NULL, crop VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C0C1B9593DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_image (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, class VARCHAR(255) DEFAULT NULL, position INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A3BCFB893DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_image_crop (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, filter VARCHAR(255) NOT NULL, crop VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1418E69B3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom_from VARCHAR(255) DEFAULT NULL, nom_to VARCHAR(255) DEFAULT NULL, email_from VARCHAR(255) NOT NULL, email_to VARCHAR(255) NOT NULL, objet LONGTEXT DEFAULT NULL, message LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, informations LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', readed TINYINT(1) NOT NULL, answered TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, answered_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6203DA5256D FOREIGN KEY (image_id) REFERENCES page_image (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE page_gallery ADD CONSTRAINT FK_BD4B93AFC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE page_gallery_crop ADD CONSTRAINT FK_C0C1B9593DA5256D FOREIGN KEY (image_id) REFERENCES page_gallery (id)');
        $this->addSql('ALTER TABLE page_image ADD CONSTRAINT FK_A3BCFB893DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE page_image_crop ADD CONSTRAINT FK_1418E69B3DA5256D FOREIGN KEY (image_id) REFERENCES page_image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page_image DROP FOREIGN KEY FK_A3BCFB893DA5256D');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93727ACA70');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93C4663E4');
        $this->addSql('ALTER TABLE page_gallery DROP FOREIGN KEY FK_BD4B93AFC4663E4');
        $this->addSql('ALTER TABLE page_gallery_crop DROP FOREIGN KEY FK_C0C1B9593DA5256D');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6203DA5256D');
        $this->addSql('ALTER TABLE page_image_crop DROP FOREIGN KEY FK_1418E69B3DA5256D');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE admin_user');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_gallery');
        $this->addSql('DROP TABLE page_gallery_crop');
        $this->addSql('DROP TABLE page_image');
        $this->addSql('DROP TABLE page_image_crop');
        $this->addSql('DROP TABLE contact');
    }
}
