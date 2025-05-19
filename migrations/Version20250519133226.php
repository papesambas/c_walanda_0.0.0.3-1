<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519133226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE prenoms (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(75) NOT NULL, UNIQUE INDEX UNIQ_E71162E38947610D (designation), INDEX IDX_PRENOMS_DESIGNATION (designation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_A069E65D8947610D ON noms (designation)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_NOMS_DESIGNATION ON noms (designation)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE prenoms
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_A069E65D8947610D ON noms
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_NOMS_DESIGNATION ON noms
        SQL);
    }
}
