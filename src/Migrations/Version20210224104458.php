<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224104458 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_translation ADD translatable_id INT DEFAULT NULL, ADD locale VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE form_translation ADD CONSTRAINT FK_4302D57E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES form (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4302D57E2C2AC5D3 ON form_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX form_translation_unique_translation ON form_translation (translatable_id, locale)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE form_translation DROP FOREIGN KEY FK_4302D57E2C2AC5D3');
        $this->addSql('DROP INDEX IDX_4302D57E2C2AC5D3 ON form_translation');
        $this->addSql('DROP INDEX form_translation_unique_translation ON form_translation');
        $this->addSql('ALTER TABLE form_translation DROP translatable_id, DROP locale');
    }
}
