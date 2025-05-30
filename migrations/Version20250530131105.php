<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530131105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE communes (id INT AUTO_INCREMENT NOT NULL, cercle_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_5C5EE2A527413AB9 (cercle_id), INDEX IDX_5C5EE2A5B03A8386 (created_by_id), INDEX IDX_5C5EE2A5896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lieu_naissances (id INT AUTO_INCREMENT NOT NULL, commune_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(75) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_49F8927F131A4F72 (commune_id), INDEX IDX_49F8927FB03A8386 (created_by_id), INDEX IDX_49F8927F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A527413AB9 FOREIGN KEY (cercle_id) REFERENCES cercles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A5B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A5896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances ADD CONSTRAINT FK_49F8927F131A4F72 FOREIGN KEY (commune_id) REFERENCES communes (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances ADD CONSTRAINT FK_49F8927FB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances ADD CONSTRAINT FK_49F8927F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE communes DROP FOREIGN KEY FK_5C5EE2A527413AB9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes DROP FOREIGN KEY FK_5C5EE2A5B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes DROP FOREIGN KEY FK_5C5EE2A5896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances DROP FOREIGN KEY FK_49F8927F131A4F72
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances DROP FOREIGN KEY FK_49F8927FB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances DROP FOREIGN KEY FK_49F8927F896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE communes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lieu_naissances
        SQL);
    }
}
