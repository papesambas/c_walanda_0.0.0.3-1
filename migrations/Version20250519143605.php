<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519143605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD nina_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B95586F33C FOREIGN KEY (nina_id) REFERENCES ninas (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B5FB13B95586F33C ON peres (nina_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B95586F33C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B5FB13B95586F33C ON peres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP nina_id
        SQL);
    }
}
