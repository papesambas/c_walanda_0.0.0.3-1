<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628122955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B15ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA95ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP FOREIGN KEY FK_BC5DBC135ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C855ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 DROP FOREIGN KEY FK_DC834A68896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 DROP FOREIGN KEY FK_DC834A68B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 DROP FOREIGN KEY FK_DC834A68B3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 DROP FOREIGN KEY FK_DC834A68E671FFEE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE scolarites3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_383B09B15ECD988B ON eleves
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP scolarite3_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2554EDA95ECD988B ON redoublements1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP scolarite3_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BC5DBC135ECD988B ON redoublements2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 DROP scolarite3_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CB5A8C855ECD988B ON redoublements3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 DROP scolarite3_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE scolarites3 (id INT AUTO_INCREMENT NOT NULL, niveau_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, scolarite2_id INT DEFAULT NULL, scolarite INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_DC834A68896DBBDE (updated_by_id), INDEX IDX_DC834A68B03A8386 (created_by_id), INDEX IDX_DC834A68B3E9C81 (niveau_id), INDEX IDX_DC834A68E671FFEE (scolarite2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 ADD CONSTRAINT FK_DC834A68896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 ADD CONSTRAINT FK_DC834A68B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 ADD CONSTRAINT FK_DC834A68B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites3 ADD CONSTRAINT FK_DC834A68E671FFEE FOREIGN KEY (scolarite2_id) REFERENCES scolarites2 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD scolarite3_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B15ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_383B09B15ECD988B ON eleves (scolarite3_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD scolarite3_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA95ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2554EDA95ECD988B ON redoublements1 (scolarite3_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD scolarite3_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements2 ADD CONSTRAINT FK_BC5DBC135ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC5DBC135ECD988B ON redoublements2 (scolarite3_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD scolarite3_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C855ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CB5A8C855ECD988B ON redoublements3 (scolarite3_id)
        SQL);
    }
}
