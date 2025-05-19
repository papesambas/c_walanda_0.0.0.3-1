<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519174831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD fullname VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2D8B408AB03A8386 ON meres (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2D8B408A896DBBDE ON meres (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD fullname VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B5FB13B9B03A8386 ON peres (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B5FB13B9896DBBDE ON peres (updated_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408AB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2D8B408AB03A8386 ON meres
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2D8B408A896DBBDE ON meres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP created_by_id, DROP updated_by_id, DROP fullname, DROP created_at, DROP updated_at, DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B5FB13B9B03A8386 ON peres
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B5FB13B9896DBBDE ON peres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP created_by_id, DROP updated_by_id, DROP fullname, DROP created_at, DROP updated_at, DROP slug
        SQL);
    }
}
