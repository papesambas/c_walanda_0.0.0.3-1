<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629165440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE departs (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, date_depart DATE NOT NULL COMMENT '(DC2Type:date_immutable)', motif VARCHAR(255) NOT NULL, ecole_destinataire VARCHAR(255) DEFAULT NULL, INDEX IDX_15CE7982A6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE dossier_eleves (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, designation VARCHAR(255) NOT NULL, INDEX IDX_D04A5D98A6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs ADD CONSTRAINT FK_15CE7982A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves ADD CONSTRAINT FK_D04A5D98A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD image_name VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE departs DROP FOREIGN KEY FK_15CE7982A6CC7B2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves DROP FOREIGN KEY FK_D04A5D98A6CC7B2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dossier_eleves
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP image_name
        SQL);
    }
}
