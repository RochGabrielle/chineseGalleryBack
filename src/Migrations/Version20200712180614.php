<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200712180614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_size (article_id INT NOT NULL, size_id INT NOT NULL, INDEX IDX_4782A36B7294869C (article_id), INDEX IDX_4782A36B498DA827 (size_id), PRIMARY KEY(article_id, size_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_size_category (article_id INT NOT NULL, size_category_id INT NOT NULL, INDEX IDX_DFAC62B87294869C (article_id), INDEX IDX_DFAC62B832F3F7D2 (size_category_id), PRIMARY KEY(article_id, size_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_theme (article_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_E0C295197294869C (article_id), INDEX IDX_E0C2951959027487 (theme_id), PRIMARY KEY(article_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_dynasty (artist_id INT NOT NULL, dynasty_id INT NOT NULL, INDEX IDX_E8524F9FB7970CF8 (artist_id), INDEX IDX_E8524F9F81F5867E (dynasty_id), PRIMARY KEY(artist_id, dynasty_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_size ADD CONSTRAINT FK_4782A36B7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_size ADD CONSTRAINT FK_4782A36B498DA827 FOREIGN KEY (size_id) REFERENCES size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_size_category ADD CONSTRAINT FK_DFAC62B87294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_size_category ADD CONSTRAINT FK_DFAC62B832F3F7D2 FOREIGN KEY (size_category_id) REFERENCES size_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_theme ADD CONSTRAINT FK_E0C295197294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_theme ADD CONSTRAINT FK_E0C2951959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_dynasty ADD CONSTRAINT FK_E8524F9FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_dynasty ADD CONSTRAINT FK_E8524F9F81F5867E FOREIGN KEY (dynasty_id) REFERENCES dynasty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD category_id INT NOT NULL, ADD artist_id INT DEFAULT NULL, ADD discount_id INT DEFAULT NULL, ADD dynasty_id INT DEFAULT NULL, ADD material_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E664C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6681F5867E FOREIGN KEY (dynasty_id) REFERENCES dynasty (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('CREATE INDEX IDX_23A0E6612469DE2 ON article (category_id)');
        $this->addSql('CREATE INDEX IDX_23A0E66B7970CF8 ON article (artist_id)');
        $this->addSql('CREATE INDEX IDX_23A0E664C7C611F ON article (discount_id)');
        $this->addSql('CREATE INDEX IDX_23A0E6681F5867E ON article (dynasty_id)');
        $this->addSql('CREATE INDEX IDX_23A0E66E308AC6F ON article (material_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE article_size');
        $this->addSql('DROP TABLE article_size_category');
        $this->addSql('DROP TABLE article_theme');
        $this->addSql('DROP TABLE artist_dynasty');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66B7970CF8');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E664C7C611F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6681F5867E');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E308AC6F');
        $this->addSql('DROP INDEX IDX_23A0E6612469DE2 ON article');
        $this->addSql('DROP INDEX IDX_23A0E66B7970CF8 ON article');
        $this->addSql('DROP INDEX IDX_23A0E664C7C611F ON article');
        $this->addSql('DROP INDEX IDX_23A0E6681F5867E ON article');
        $this->addSql('DROP INDEX IDX_23A0E66E308AC6F ON article');
        $this->addSql('ALTER TABLE article DROP category_id, DROP artist_id, DROP discount_id, DROP dynasty_id, DROP material_id');
    }
}
