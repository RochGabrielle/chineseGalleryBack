<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315081650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, placeholder VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_4302D57E2C2AC5D3 (translatable_id), UNIQUE INDEX form_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form_translation ADD CONSTRAINT FK_4302D57E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES form (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD form_id INT DEFAULT NULL, ADD size VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E665FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('CREATE INDEX IDX_23A0E665FF69B7D ON article (form_id)');
        $this->addSql('ALTER TABLE dynasty ADD small VARCHAR(255) DEFAULT NULL, ADD big VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dynasty_translation ADD introduction LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E665FF69B7D');
        $this->addSql('ALTER TABLE form_translation DROP FOREIGN KEY FK_4302D57E2C2AC5D3');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE form_translation');
        $this->addSql('DROP INDEX IDX_23A0E665FF69B7D ON article');
        $this->addSql('ALTER TABLE article DROP form_id, DROP size');
        $this->addSql('ALTER TABLE dynasty DROP small, DROP big');
        $this->addSql('ALTER TABLE dynasty_translation DROP introduction');
    }
}
