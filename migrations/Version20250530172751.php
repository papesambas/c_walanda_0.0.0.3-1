<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530172751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE etablissements (id INT AUTO_INCREMENT NOT NULL, enseignement_id INT NOT NULL, designation VARCHAR(100) NOT NULL, forme_juridique VARCHAR(30) NOT NULL, decision_creation VARCHAR(30) NOT NULL, decision_ouverture VARCHAR(30) NOT NULL, date_ouverture DATE DEFAULT NULL COMMENT '(DC2Type:date_immutable)', numero_social VARCHAR(30) DEFAULT NULL, numero_fiscal VARCHAR(30) DEFAULT NULL, compte_bancaire VARCHAR(60) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(23) NOT NULL, telephone_mobile VARCHAR(25) DEFAULT NULL, email VARCHAR(100) NOT NULL, capacite INT DEFAULT NULL, effectif INT DEFAULT NULL, INDEX IDX_29F65EB1ABEC3B20 (enseignement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB1ABEC3B20 FOREIGN KEY (enseignement_id) REFERENCES enseignements (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB1ABEC3B20
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE etablissements
        SQL);
    }
}
