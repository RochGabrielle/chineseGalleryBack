<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924162140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, INDEX IDX_7BA2F5EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, artist_id INT DEFAULT NULL, discount_id INT DEFAULT NULL, dynasty_id INT DEFAULT NULL, material_id INT DEFAULT NULL, museum_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, birth SMALLINT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, small VARCHAR(255) DEFAULT NULL, big VARCHAR(255) DEFAULT NULL, status INT DEFAULT NULL, INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E66B7970CF8 (artist_id), INDEX IDX_23A0E664C7C611F (discount_id), INDEX IDX_23A0E6681F5867E (dynasty_id), INDEX IDX_23A0E66E308AC6F (material_id), INDEX IDX_23A0E664B52E5B5 (museum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_theme (article_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_E0C295197294869C (article_id), INDEX IDX_E0C2951959027487 (theme_id), PRIMARY KEY(article_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, title LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_2EEA2F082C2AC5D3 (translatable_id), UNIQUE INDEX article_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth SMALLINT DEFAULT NULL, death SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_dynasty (artist_id INT NOT NULL, dynasty_id INT NOT NULL, INDEX IDX_E8524F9FB7970CF8 (artist_id), INDEX IDX_E8524F9F81F5867E (dynasty_id), PRIMARY KEY(artist_id, dynasty_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, name LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_9D53F3282C2AC5D3 (translatable_id), UNIQUE INDEX artist_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_3F207042C2AC5D3 (translatable_id), UNIQUE INDEX category_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, discountrate DOUBLE PRECISION DEFAULT NULL, placeholder VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_F618B1572C2AC5D3 (translatable_id), UNIQUE INDEX discount_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynasty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth SMALLINT DEFAULT NULL, death SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynasty_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, name LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_84EDAA832C2AC5D3 (translatable_id), UNIQUE INDEX dynasty_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_177577672C2AC5D3 (translatable_id), UNIQUE INDEX material_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_430137FC2C2AC5D3 (translatable_id), UNIQUE INDEX media_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE museum (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, linkname VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE museum_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_D264407C2C2AC5D3 (translatable_id), UNIQUE INDEX museum_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE navigation (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE navigation_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_5177797B2C2AC5D3 (translatable_id), UNIQUE INDEX navigation_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, sizecategory_id INT DEFAULT NULL, article_id INT DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F7C0246AABE2EEEF (sizecategory_id), INDEX IDX_F7C0246A7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sizecategory (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sizecategory_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_5E43C3E02C2AC5D3 (translatable_id), UNIQUE INDEX sizecategory_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slideshow (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, desktop VARCHAR(255) DEFAULT NULL, tablet VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, status INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slideshow_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_C7D4AD1C2C2AC5D3 (translatable_id), UNIQUE INDEX slideshow_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, placeholder VARCHAR(255) NOT NULL, INDEX IDX_9775E708EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_5C4256602C2AC5D3 (translatable_id), UNIQUE INDEX theme_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_article (user_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_5A37106CA76ED395 (user_id), INDEX IDX_5A37106C7294869C (article_id), PRIMARY KEY(user_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E664C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6681F5867E FOREIGN KEY (dynasty_id) REFERENCES dynasty (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E664B52E5B5 FOREIGN KEY (museum_id) REFERENCES museum (id)');
        $this->addSql('ALTER TABLE article_theme ADD CONSTRAINT FK_E0C295197294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_theme ADD CONSTRAINT FK_E0C2951959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_dynasty ADD CONSTRAINT FK_E8524F9FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_dynasty ADD CONSTRAINT FK_E8524F9F81F5867E FOREIGN KEY (dynasty_id) REFERENCES dynasty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_translation ADD CONSTRAINT FK_9D53F3282C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F207042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discount_translation ADD CONSTRAINT FK_F618B1572C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES discount (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dynasty_translation ADD CONSTRAINT FK_84EDAA832C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES dynasty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE material_translation ADD CONSTRAINT FK_177577672C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_translation ADD CONSTRAINT FK_430137FC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE museum_translation ADD CONSTRAINT FK_D264407C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES museum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE navigation_translation ADD CONSTRAINT FK_5177797B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES navigation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246AABE2EEEF FOREIGN KEY (sizecategory_id) REFERENCES sizecategory (id)');
        $this->addSql('ALTER TABLE size ADD CONSTRAINT FK_F7C0246A7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE sizecategory_translation ADD CONSTRAINT FK_5E43C3E02C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sizecategory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slideshow_translation ADD CONSTRAINT FK_C7D4AD1C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES slideshow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E708EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE theme_translation ADD CONSTRAINT FK_5C4256602C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_theme DROP FOREIGN KEY FK_E0C295197294869C');
        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_2EEA2F082C2AC5D3');
        $this->addSql('ALTER TABLE size DROP FOREIGN KEY FK_F7C0246A7294869C');
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106C7294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66B7970CF8');
        $this->addSql('ALTER TABLE artist_dynasty DROP FOREIGN KEY FK_E8524F9FB7970CF8');
        $this->addSql('ALTER TABLE artist_translation DROP FOREIGN KEY FK_9D53F3282C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F207042C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E664C7C611F');
        $this->addSql('ALTER TABLE discount_translation DROP FOREIGN KEY FK_F618B1572C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6681F5867E');
        $this->addSql('ALTER TABLE artist_dynasty DROP FOREIGN KEY FK_E8524F9F81F5867E');
        $this->addSql('ALTER TABLE dynasty_translation DROP FOREIGN KEY FK_84EDAA832C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E308AC6F');
        $this->addSql('ALTER TABLE material_translation DROP FOREIGN KEY FK_177577672C2AC5D3');
        $this->addSql('ALTER TABLE media_translation DROP FOREIGN KEY FK_430137FC2C2AC5D3');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E708EA9FDD75');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E664B52E5B5');
        $this->addSql('ALTER TABLE museum_translation DROP FOREIGN KEY FK_D264407C2C2AC5D3');
        $this->addSql('ALTER TABLE navigation_translation DROP FOREIGN KEY FK_5177797B2C2AC5D3');
        $this->addSql('ALTER TABLE size DROP FOREIGN KEY FK_F7C0246AABE2EEEF');
        $this->addSql('ALTER TABLE sizecategory_translation DROP FOREIGN KEY FK_5E43C3E02C2AC5D3');
        $this->addSql('ALTER TABLE slideshow_translation DROP FOREIGN KEY FK_C7D4AD1C2C2AC5D3');
        $this->addSql('ALTER TABLE article_theme DROP FOREIGN KEY FK_E0C2951959027487');
        $this->addSql('ALTER TABLE theme_translation DROP FOREIGN KEY FK_5C4256602C2AC5D3');
        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EBA76ED395');
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CA76ED395');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_theme');
        $this->addSql('DROP TABLE article_translation');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE artist_dynasty');
        $this->addSql('DROP TABLE artist_translation');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE discount_translation');
        $this->addSql('DROP TABLE dynasty');
        $this->addSql('DROP TABLE dynasty_translation');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE material_translation');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_translation');
        $this->addSql('DROP TABLE museum');
        $this->addSql('DROP TABLE museum_translation');
        $this->addSql('DROP TABLE navigation');
        $this->addSql('DROP TABLE navigation_translation');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP TABLE sizecategory');
        $this->addSql('DROP TABLE sizecategory_translation');
        $this->addSql('DROP TABLE slideshow');
        $this->addSql('DROP TABLE slideshow_translation');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_translation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_article');
    }
}
