<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617160951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 ADD scolarite2_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 ADD CONSTRAINT FK_DC834A68E671FFEE FOREIGN KEY (scolarite2_id) REFERENCES scolarites2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DC834A68E671FFEE ON scolarites3 (scolarite2_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 DROP FOREIGN KEY FK_DC834A68E671FFEE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DC834A68E671FFEE ON scolarites3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 DROP scolarite2_id
        SQL);
    }
}
