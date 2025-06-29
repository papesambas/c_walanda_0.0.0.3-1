<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250623164523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE redoublements2 (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, niveau_id INT NOT NULL, scolarite1_id INT NOT NULL, scolarite2_id INT NOT NULL, scolarite3_id INT NOT NULL, redoublement1_id INT NOT NULL, designation VARCHAR(75) NOT NULL, INDEX IDX_BC5DBC135EC1162 (cycle_id), INDEX IDX_BC5DBC13B3E9C81 (niveau_id), INDEX IDX_BC5DBC13F4C45000 (scolarite1_id), INDEX IDX_BC5DBC13E671FFEE (scolarite2_id), INDEX IDX_BC5DBC135ECD988B (scolarite3_id), INDEX IDX_BC5DBC136D13ADFD (redoublement1_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE redoublements3 (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, niveau_id INT NOT NULL, scolarite1_id INT NOT NULL, scolarite2_id INT NOT NULL, scolarite3_id INT NOT NULL, redoublement2_id INT NOT NULL, INDEX IDX_CB5A8C855EC1162 (cycle_id), INDEX IDX_CB5A8C85B3E9C81 (niveau_id), INDEX IDX_CB5A8C85F4C45000 (scolarite1_id), INDEX IDX_CB5A8C85E671FFEE (scolarite2_id), INDEX IDX_CB5A8C855ECD988B (scolarite3_id), INDEX IDX_CB5A8C857FA60213 (redoublement2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC135EC1162 FOREIGN KEY (cycle_id) REFERENCES cycles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC13B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC13F4C45000 FOREIGN KEY (scolarite1_id) REFERENCES scolarites1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC13E671FFEE FOREIGN KEY (scolarite2_id) REFERENCES scolarites2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC135ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC136D13ADFD FOREIGN KEY (redoublement1_id) REFERENCES redoublements1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C855EC1162 FOREIGN KEY (cycle_id) REFERENCES cycles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C85B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C85F4C45000 FOREIGN KEY (scolarite1_id) REFERENCES scolarites1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C85E671FFEE FOREIGN KEY (scolarite2_id) REFERENCES scolarites2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C855ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C857FA60213 FOREIGN KEY (redoublement2_id) REFERENCES redoublements2 (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC135EC1162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC13B3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC13F4C45000
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC13E671FFEE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC135ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC136D13ADFD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C855EC1162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C85B3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C85F4C45000
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C85E671FFEE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C855ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C857FA60213
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE redoublements2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE redoublements3
        SQL);
    }
}
