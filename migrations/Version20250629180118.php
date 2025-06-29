<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629180118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE santes (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, maladie VARCHAR(255) NOT NULL, medecin VARCHAR(50) DEFAULT NULL, numero_urgence VARCHAR(23) NOT NULL, centre_sante VARCHAR(150) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_C1A17FE9A6CC7B2 (eleve_id), INDEX IDX_C1A17FE9B03A8386 (created_by_id), INDEX IDX_C1A17FE9896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE santes ADD CONSTRAINT FK_C1A17FE9A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE santes ADD CONSTRAINT FK_C1A17FE9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE santes ADD CONSTRAINT FK_C1A17FE9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs ADD CONSTRAINT FK_15CE7982B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs ADD CONSTRAINT FK_15CE7982896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_15CE7982B03A8386 ON departs (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_15CE7982896DBBDE ON departs (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves ADD CONSTRAINT FK_D04A5D98B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves ADD CONSTRAINT FK_D04A5D98896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D04A5D98B03A8386 ON dossier_eleves (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D04A5D98896DBBDE ON dossier_eleves (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD is_handicap TINYINT(1) NOT NULL, ADD nature_handicap VARCHAR(50) DEFAULT NULL, ADD statut_finance VARCHAR(8) NOT NULL, ADD date_inscription DATE DEFAULT NULL COMMENT '(DC2Type:date_immutable)', ADD date_recrutement DATE NOT NULL COMMENT '(DC2Type:date_immutable)', ADD matricule VARCHAR(50) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE santes DROP FOREIGN KEY FK_C1A17FE9A6CC7B2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE santes DROP FOREIGN KEY FK_C1A17FE9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE santes DROP FOREIGN KEY FK_C1A17FE9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE santes
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs DROP FOREIGN KEY FK_15CE7982B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs DROP FOREIGN KEY FK_15CE7982896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_15CE7982B03A8386 ON departs
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_15CE7982896DBBDE ON departs
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departs DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves DROP FOREIGN KEY FK_D04A5D98B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves DROP FOREIGN KEY FK_D04A5D98896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D04A5D98B03A8386 ON dossier_eleves
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D04A5D98896DBBDE ON dossier_eleves
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE dossier_eleves DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP is_handicap, DROP nature_handicap, DROP statut_finance, DROP date_inscription, DROP date_recrutement, DROP matricule
        SQL);
    }
}
