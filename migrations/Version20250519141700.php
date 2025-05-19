<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519141700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE telephone2 (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, numero VARCHAR(23) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_727A199B03A8386 (created_by_id), INDEX IDX_727A199896DBBDE (updated_by_id), INDEX IDX_TELEPHONES_2_NUMERO (numero), UNIQUE INDEX UNIQ_TELEPHONES_2_NUMERO (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 ADD CONSTRAINT FK_727A199B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 ADD CONSTRAINT FK_727A199896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms RENAME INDEX uniq_a069e65d8947610d TO UNIQ_NOMS_DESIGNATION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms ADD CONSTRAINT FK_E71162E3B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms ADD CONSTRAINT FK_E71162E3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E71162E3B03A8386 ON prenoms (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E71162E3896DBBDE ON prenoms (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms RENAME INDEX uniq_e71162e38947610d TO UNIQ_PRENOMS_DESIGNATION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions ADD CONSTRAINT FK_2FDA85FAB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions ADD CONSTRAINT FK_2FDA85FA896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2FDA85FAB03A8386 ON professions (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2FDA85FA896DBBDE ON professions (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions RENAME INDEX uniq_2fda85fa8947610d TO UNIQ_PROFESSIONS_DESIGNATION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', ADD slug VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 ADD CONSTRAINT FK_9E2EF023B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 ADD CONSTRAINT FK_9E2EF023896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9E2EF023B03A8386 ON telephone1 (created_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9E2EF023896DBBDE ON telephone1 (updated_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 RENAME INDEX uniq_9e2ef023f55ae19e TO UNIQ_TELEPHONES_1_NUMERO
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 DROP FOREIGN KEY FK_727A199B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 DROP FOREIGN KEY FK_727A199896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE telephone2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 DROP FOREIGN KEY FK_9E2EF023B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 DROP FOREIGN KEY FK_9E2EF023896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9E2EF023B03A8386 ON telephone1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9E2EF023896DBBDE ON telephone1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 RENAME INDEX uniq_telephones_1_numero TO UNIQ_9E2EF023F55AE19E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions DROP FOREIGN KEY FK_2FDA85FAB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions DROP FOREIGN KEY FK_2FDA85FA896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2FDA85FAB03A8386 ON professions
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2FDA85FA896DBBDE ON professions
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions RENAME INDEX uniq_professions_designation TO UNIQ_2FDA85FA8947610D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms RENAME INDEX uniq_noms_designation TO UNIQ_A069E65D8947610D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms DROP FOREIGN KEY FK_E71162E3B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms DROP FOREIGN KEY FK_E71162E3896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E71162E3B03A8386 ON prenoms
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E71162E3896DBBDE ON prenoms
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at, DROP slug
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms RENAME INDEX uniq_prenoms_designation TO UNIQ_E71162E38947610D
        SQL);
    }
}
