<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212134603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte ADD date_create DATETIME NOT NULL');
        $this->addSql('ALTER TABLE depot ADD num_com_id INT NOT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCCDAD588F FOREIGN KEY (num_com_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_47948BBCCDAD588F ON depot (num_com_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP date_create');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCCDAD588F');
        $this->addSql('DROP INDEX IDX_47948BBCCDAD588F ON depot');
        $this->addSql('ALTER TABLE depot DROP num_com_id');
    }
}
