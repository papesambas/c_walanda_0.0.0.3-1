<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530175929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cycles (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, effectif INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_72B88B248947610D (designation), INDEX IDX_72B88B24B03A8386 (created_by_id), INDEX IDX_72B88B24896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cycles_enseignements (cycles_id INT NOT NULL, enseignements_id INT NOT NULL, INDEX IDX_61407BF144C85140 (cycles_id), INDEX IDX_61407BF1DCB471D6 (enseignements_id), PRIMARY KEY(cycles_id, enseignements_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles ADD CONSTRAINT FK_72B88B24B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles ADD CONSTRAINT FK_72B88B24896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements ADD CONSTRAINT FK_61407BF144C85140 FOREIGN KEY (cycles_id) REFERENCES cycles (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements ADD CONSTRAINT FK_61407BF1DCB471D6 FOREIGN KEY (enseignements_id) REFERENCES enseignements (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL, CHANGE decision_ouverture decision_ouverture VARCHAR(30) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB1B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_29F65EB18947610D ON etablissements (designation)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_29F65EB1DA324A4A ON etablissements (decision_creation)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_29F65EB182AADFCF ON etablissements (decision_ouverture)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29F65EB1B03A8386 ON etablissements (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29F65EB1896DBBDE ON etablissements (updated_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles DROP FOREIGN KEY FK_72B88B24B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles DROP FOREIGN KEY FK_72B88B24896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements DROP FOREIGN KEY FK_61407BF144C85140
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements DROP FOREIGN KEY FK_61407BF1DCB471D6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cycles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cycles_enseignements
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB1B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB1896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_29F65EB18947610D ON etablissements
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_29F65EB1DA324A4A ON etablissements
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_29F65EB182AADFCF ON etablissements
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_29F65EB1B03A8386 ON etablissements
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_29F65EB1896DBBDE ON etablissements
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug, CHANGE decision_ouverture decision_ouverture VARCHAR(30) NOT NULL
        SQL);
    }
}
