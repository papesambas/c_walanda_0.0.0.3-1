<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529164012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD telephone2_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A86957E8B FOREIGN KEY (telephone2_id) REFERENCES telephone2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2D8B408A86957E8B ON meres (telephone2_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A86957E8B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2D8B408A86957E8B ON meres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP telephone2_id
        SQL);
    }
}
