<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529150555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A86957E8B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A9420D165
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2D8B408A9420D165 ON meres
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2D8B408A86957E8B ON meres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD mere_telephone2_id INT DEFAULT NULL, DROP telephone1_id, CHANGE telephone2_id mere_telephone1_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A3784F6EA FOREIGN KEY (mere_telephone1_id) REFERENCES telephone1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A25315904 FOREIGN KEY (mere_telephone2_id) REFERENCES telephone2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2D8B408A3784F6EA ON meres (mere_telephone1_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2D8B408A25315904 ON meres (mere_telephone2_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B986957E8B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B99420D165
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B5FB13B99420D165 ON peres
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B5FB13B986957E8B ON peres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres CHANGE telephone1_id pere_telephone1_id INT NOT NULL, CHANGE telephone2_id pere_telephone2_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9EA8C6519 FOREIGN KEY (pere_telephone1_id) REFERENCES telephone1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9F839CAF7 FOREIGN KEY (pere_telephone2_id) REFERENCES telephone2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B5FB13B9EA8C6519 ON peres (pere_telephone1_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B5FB13B9F839CAF7 ON peres (pere_telephone2_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A3784F6EA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A25315904
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2D8B408A3784F6EA ON meres
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2D8B408A25315904 ON meres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD telephone1_id INT NOT NULL, ADD telephone2_id INT DEFAULT NULL, DROP mere_telephone1_id, DROP mere_telephone2_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A86957E8B FOREIGN KEY (telephone2_id) REFERENCES telephone2 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A9420D165 FOREIGN KEY (telephone1_id) REFERENCES telephone1 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2D8B408A9420D165 ON meres (telephone1_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2D8B408A86957E8B ON meres (telephone2_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9EA8C6519
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9F839CAF7
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B5FB13B9EA8C6519 ON peres
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B5FB13B9F839CAF7 ON peres
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres CHANGE pere_telephone1_id telephone1_id INT NOT NULL, CHANGE pere_telephone2_id telephone2_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B986957E8B FOREIGN KEY (telephone2_id) REFERENCES telephone2 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B99420D165 FOREIGN KEY (telephone1_id) REFERENCES telephone1 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B5FB13B99420D165 ON peres (telephone1_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B5FB13B986957E8B ON peres (telephone2_id)
        SQL);
    }
}
