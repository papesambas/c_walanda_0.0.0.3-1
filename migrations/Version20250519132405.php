<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519132405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE noms (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_A069E65DB03A8386 (created_by_id), INDEX IDX_A069E65D896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms ADD CONSTRAINT FK_A069E65DB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms ADD CONSTRAINT FK_A069E65D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE noms DROP FOREIGN KEY FK_A069E65DB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms DROP FOREIGN KEY FK_A069E65D896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE noms
        SQL);
    }
}
