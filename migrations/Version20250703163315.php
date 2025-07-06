<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703163315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recup_hist_departs ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recup_hist_departs ADD CONSTRAINT FK_7C79DEC8B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recup_hist_departs ADD CONSTRAINT FK_7C79DEC8896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C79DEC8B03A8386 ON recup_hist_departs (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7C79DEC8896DBBDE ON recup_hist_departs (updated_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recup_hist_departs DROP FOREIGN KEY FK_7C79DEC8B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recup_hist_departs DROP FOREIGN KEY FK_7C79DEC8896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7C79DEC8B03A8386 ON recup_hist_departs
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7C79DEC8896DBBDE ON recup_hist_departs
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recup_hist_departs DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
    }
}
