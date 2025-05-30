<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530130422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cercles (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_45C1718D8947610D (designation), INDEX IDX_45C1718D98260155 (region_id), INDEX IDX_45C1718DB03A8386 (created_by_id), INDEX IDX_45C1718D896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles ADD CONSTRAINT FK_45C1718D98260155 FOREIGN KEY (region_id) REFERENCES regions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles ADD CONSTRAINT FK_45C1718DB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles ADD CONSTRAINT FK_45C1718D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles DROP FOREIGN KEY FK_45C1718D98260155
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles DROP FOREIGN KEY FK_45C1718DB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles DROP FOREIGN KEY FK_45C1718D896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cercles
        SQL);
    }
}
