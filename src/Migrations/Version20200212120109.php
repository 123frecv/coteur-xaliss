<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212120109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, date_create DATETIME NOT NULL, treme LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat_partenaire (contrat_id INT NOT NULL, partenaire_id INT NOT NULL, INDEX IDX_8FC956181823061F (contrat_id), INDEX IDX_8FC9561898DE13AC (partenaire_id), PRIMARY KEY(contrat_id, partenaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_depot DATETIME NOT NULL, INDEX IDX_47948BBCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, ninea VARCHAR(50) NOT NULL, rmc VARCHAR(50) NOT NULL, tel VARCHAR(50) NOT NULL, logo VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrat_partenaire ADD CONSTRAINT FK_8FC956181823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contrat_partenaire ADD CONSTRAINT FK_8FC9561898DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX IDX_CFF6526066B9F512 ON compte');
        $this->addSql('ALTER TABLE compte ADD user_id INT NOT NULL, CHANGE partenaire_compte_id partenaire_comp_id INT NOT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526052FCC51C FOREIGN KEY (partenaire_comp_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFF6526052FCC51C ON compte (partenaire_comp_id)');
        $this->addSql('CREATE INDEX IDX_CFF65260A76ED395 ON compte (user_id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494CE34BEC FOREIGN KEY (part_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494CE34BEC ON user (part_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contrat_partenaire DROP FOREIGN KEY FK_8FC956181823061F');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526052FCC51C');
        $this->addSql('ALTER TABLE contrat_partenaire DROP FOREIGN KEY FK_8FC9561898DE13AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494CE34BEC');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE contrat_partenaire');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260A76ED395');
        $this->addSql('DROP INDEX IDX_CFF6526052FCC51C ON compte');
        $this->addSql('DROP INDEX IDX_CFF65260A76ED395 ON compte');
        $this->addSql('ALTER TABLE compte ADD partenaire_compte_id INT NOT NULL, DROP partenaire_comp_id, DROP user_id');
        $this->addSql('CREATE INDEX IDX_CFF6526066B9F512 ON compte (partenaire_compte_id)');
        $this->addSql('DROP INDEX IDX_8D93D6494CE34BEC ON user');
    }
}
