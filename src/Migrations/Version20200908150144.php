<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908150144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_translation ADD translatable_id INT DEFAULT NULL, ADD locale VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE media_translation ADD CONSTRAINT FK_430137FC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_430137FC2C2AC5D3 ON media_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX media_translation_unique_translation ON media_translation (translatable_id, locale)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_translation DROP FOREIGN KEY FK_430137FC2C2AC5D3');
        $this->addSql('DROP INDEX IDX_430137FC2C2AC5D3 ON media_translation');
        $this->addSql('DROP INDEX media_translation_unique_translation ON media_translation');
        $this->addSql('ALTER TABLE media_translation DROP translatable_id, DROP locale');
    }
}
