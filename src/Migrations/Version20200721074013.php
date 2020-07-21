<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721074013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, artist_id INT DEFAULT NULL, discount_id INT DEFAULT NULL, dynasty_id INT DEFAULT NULL, material_id INT NOT NULL, reference VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, date_of_creation SMALLINT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E66B7970CF8 (artist_id), INDEX IDX_23A0E664C7C611F (discount_id), INDEX IDX_23A0E6681F5867E (dynasty_id), INDEX IDX_23A0E66E308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_size (article_id INT NOT NULL, size_id INT NOT NULL, INDEX IDX_4782A36B7294869C (article_id), INDEX IDX_4782A36B498DA827 (size_id), PRIMARY KEY(article_id, size_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_size_category (article_id INT NOT NULL, size_category_id INT NOT NULL, INDEX IDX_DFAC62B87294869C (article_id), INDEX IDX_DFAC62B832F3F7D2 (size_category_id), PRIMARY KEY(article_id, size_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_theme (article_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_E0C295197294869C (article_id), INDEX IDX_E0C2951959027487 (theme_id), PRIMARY KEY(article_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_2EEA2F082C2AC5D3 (translatable_id), UNIQUE INDEX article_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth_date SMALLINT DEFAULT NULL, death_date SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_dynasty (artist_id INT NOT NULL, dynasty_id INT NOT NULL, INDEX IDX_E8524F9FB7970CF8 (artist_id), INDEX IDX_E8524F9F81F5867E (dynasty_id), PRIMARY KEY(artist_id, dynasty_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_9D53F3282C2AC5D3 (translatable_id), UNIQUE INDEX artist_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_3F207042C2AC5D3 (translatable_id), UNIQUE INDEX category_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, discount_value DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynasty (id INT AUTO_INCREMENT NOT NULL, dynasty_name VARCHAR(255) NOT NULL, date_beginning SMALLINT DEFAULT NULL, date_end SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dynasty_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_84EDAA832C2AC5D3 (translatable_id), UNIQUE INDEX dynasty_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_177577672C2AC5D3 (translatable_id), UNIQUE INDEX material_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE navigation (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE navigation_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_5177797B2C2AC5D3 (translatable_id), UNIQUE INDEX navigation_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE size_category (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_5C4256602C2AC5D3 (translatable_id), UNIQUE INDEX theme_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E664C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6681F5867E FOREIGN KEY (dynasty_id) REFERENCES dynasty (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('ALTER TABLE article_size ADD CONSTRAINT FK_4782A36B7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_size ADD CONSTRAINT FK_4782A36B498DA827 FOREIGN KEY (size_id) REFERENCES size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_size_category ADD CONSTRAINT FK_DFAC62B87294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_size_category ADD CONSTRAINT FK_DFAC62B832F3F7D2 FOREIGN KEY (size_category_id) REFERENCES size_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_theme ADD CONSTRAINT FK_E0C295197294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_theme ADD CONSTRAINT FK_E0C2951959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_dynasty ADD CONSTRAINT FK_E8524F9FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_dynasty ADD CONSTRAINT FK_E8524F9F81F5867E FOREIGN KEY (dynasty_id) REFERENCES dynasty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_translation ADD CONSTRAINT FK_9D53F3282C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F207042C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dynasty_translation ADD CONSTRAINT FK_84EDAA832C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES dynasty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE material_translation ADD CONSTRAINT FK_177577672C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE navigation_translation ADD CONSTRAINT FK_5177797B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES navigation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_translation ADD CONSTRAINT FK_5C4256602C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES theme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_size DROP FOREIGN KEY FK_4782A36B7294869C');
        $this->addSql('ALTER TABLE article_size_category DROP FOREIGN KEY FK_DFAC62B87294869C');
        $this->addSql('ALTER TABLE article_theme DROP FOREIGN KEY FK_E0C295197294869C');
        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_2EEA2F082C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66B7970CF8');
        $this->addSql('ALTER TABLE artist_dynasty DROP FOREIGN KEY FK_E8524F9FB7970CF8');
        $this->addSql('ALTER TABLE artist_translation DROP FOREIGN KEY FK_9D53F3282C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F207042C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E664C7C611F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6681F5867E');
        $this->addSql('ALTER TABLE artist_dynasty DROP FOREIGN KEY FK_E8524F9F81F5867E');
        $this->addSql('ALTER TABLE dynasty_translation DROP FOREIGN KEY FK_84EDAA832C2AC5D3');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E308AC6F');
        $this->addSql('ALTER TABLE material_translation DROP FOREIGN KEY FK_177577672C2AC5D3');
        $this->addSql('ALTER TABLE navigation_translation DROP FOREIGN KEY FK_5177797B2C2AC5D3');
        $this->addSql('ALTER TABLE article_size DROP FOREIGN KEY FK_4782A36B498DA827');
        $this->addSql('ALTER TABLE article_size_category DROP FOREIGN KEY FK_DFAC62B832F3F7D2');
        $this->addSql('ALTER TABLE article_theme DROP FOREIGN KEY FK_E0C2951959027487');
        $this->addSql('ALTER TABLE theme_translation DROP FOREIGN KEY FK_5C4256602C2AC5D3');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_size');
        $this->addSql('DROP TABLE article_size_category');
        $this->addSql('DROP TABLE article_theme');
        $this->addSql('DROP TABLE article_translation');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE artist_dynasty');
        $this->addSql('DROP TABLE artist_translation');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE dynasty');
        $this->addSql('DROP TABLE dynasty_translation');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE material_translation');
        $this->addSql('DROP TABLE navigation');
        $this->addSql('DROP TABLE navigation_translation');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP TABLE size_category');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_translation');
    }
}
