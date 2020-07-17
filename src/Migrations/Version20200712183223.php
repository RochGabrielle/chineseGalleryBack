<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200712183223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_2EEA2F082C2AC5D3 (translatable_id), UNIQUE INDEX article_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_9D53F3282C2AC5D3 (translatable_id), UNIQUE INDEX artist_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, category_name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_3F207042C2AC5D3 (translatable_id), UNIQUE INDEX category_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynasty_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_84EDAA832C2AC5D3 (translatable_id), UNIQUE INDEX dynasty_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, material_name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_177577672C2AC5D3 (translatable_id), UNIQUE INDEX material_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, theme_name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_5C4256602C2AC5D3 (translatable_id), UNIQUE INDEX theme_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_translation ADD CONSTRAINT FK_9D53F3282C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F207042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dynasty_translation ADD CONSTRAINT FK_84EDAA832C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES dynasty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE material_translation ADD CONSTRAINT FK_177577672C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_translation ADD CONSTRAINT FK_5C4256602C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES theme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE article_translation');
        $this->addSql('DROP TABLE artist_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE dynasty_translation');
        $this->addSql('DROP TABLE material_translation');
        $this->addSql('DROP TABLE theme_translation');
    }
}
