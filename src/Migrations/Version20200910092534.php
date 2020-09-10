<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200910092534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD museum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E664B52E5B5 FOREIGN KEY (museum_id) REFERENCES museum (id)');
        $this->addSql('CREATE INDEX IDX_23A0E664B52E5B5 ON article (museum_id)');
        $this->addSql('ALTER TABLE museum DROP FOREIGN KEY FK_62474477B549C760');
        $this->addSql('DROP INDEX UNIQ_62474477B549C760 ON museum');
        $this->addSql('ALTER TABLE museum DROP one_to_one_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E664B52E5B5');
        $this->addSql('DROP INDEX IDX_23A0E664B52E5B5 ON article');
        $this->addSql('ALTER TABLE article DROP museum_id');
        $this->addSql('ALTER TABLE museum ADD one_to_one_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE museum ADD CONSTRAINT FK_62474477B549C760 FOREIGN KEY (one_to_one_id) REFERENCES article (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62474477B549C760 ON museum (one_to_one_id)');
    }
}
