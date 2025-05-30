<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530153010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE enseignements (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_89D792808947610D (designation), INDEX IDX_89D79280B03A8386 (created_by_id), INDEX IDX_89D79280896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE enseignements_statuts (enseignements_id INT NOT NULL, statuts_id INT NOT NULL, INDEX IDX_ACDCF820DCB471D6 (enseignements_id), INDEX IDX_ACDCF820E0EA5904 (statuts_id), PRIMARY KEY(enseignements_id, statuts_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements ADD CONSTRAINT FK_89D79280B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements ADD CONSTRAINT FK_89D79280896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts ADD CONSTRAINT FK_ACDCF820DCB471D6 FOREIGN KEY (enseignements_id) REFERENCES enseignements (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts ADD CONSTRAINT FK_ACDCF820E0EA5904 FOREIGN KEY (statuts_id) REFERENCES statuts (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements DROP FOREIGN KEY FK_89D79280B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements DROP FOREIGN KEY FK_89D79280896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts DROP FOREIGN KEY FK_ACDCF820DCB471D6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts DROP FOREIGN KEY FK_ACDCF820E0EA5904
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enseignements
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enseignements_statuts
        SQL);
    }
}
