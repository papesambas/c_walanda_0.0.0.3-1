<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250531005212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1483A5E9B03A8386 ON users (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1483A5E9896DBBDE ON users (updated_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1483A5E9B03A8386 ON users
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1483A5E9896DBBDE ON users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
    }
}
