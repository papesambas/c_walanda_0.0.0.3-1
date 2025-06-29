<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617223733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE redoublements1 (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, niveau_id INT NOT NULL, scolarite1_id INT NOT NULL, scolarite2_id INT NOT NULL, scolarite3_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(75) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_2554EDA95EC1162 (cycle_id), INDEX IDX_2554EDA9B3E9C81 (niveau_id), INDEX IDX_2554EDA9F4C45000 (scolarite1_id), INDEX IDX_2554EDA9E671FFEE (scolarite2_id), INDEX IDX_2554EDA95ECD988B (scolarite3_id), INDEX IDX_2554EDA9B03A8386 (created_by_id), INDEX IDX_2554EDA9896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA95EC1162 FOREIGN KEY (cycle_id) REFERENCES cycles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9F4C45000 FOREIGN KEY (scolarite1_id) REFERENCES scolarites1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9E671FFEE FOREIGN KEY (scolarite2_id) REFERENCES scolarites2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA95ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA95EC1162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9B3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9F4C45000
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9E671FFEE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA95ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE redoublements1
        SQL);
    }
}
