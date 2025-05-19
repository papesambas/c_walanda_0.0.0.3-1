<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519142248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE peres (id INT AUTO_INCREMENT NOT NULL, nom_id INT NOT NULL, prenom_id INT NOT NULL, profession_id INT NOT NULL, telephone1_id INT NOT NULL, telephone2_id INT DEFAULT NULL, INDEX IDX_B5FB13B9C8121CE9 (nom_id), INDEX IDX_B5FB13B958819F9E (prenom_id), INDEX IDX_B5FB13B9FDEF8996 (profession_id), UNIQUE INDEX UNIQ_B5FB13B99420D165 (telephone1_id), UNIQUE INDEX UNIQ_B5FB13B986957E8B (telephone2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9C8121CE9 FOREIGN KEY (nom_id) REFERENCES noms (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B958819F9E FOREIGN KEY (prenom_id) REFERENCES prenoms (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9FDEF8996 FOREIGN KEY (profession_id) REFERENCES professions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B99420D165 FOREIGN KEY (telephone1_id) REFERENCES telephone1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B986957E8B FOREIGN KEY (telephone2_id) REFERENCES telephone2 (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9C8121CE9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B958819F9E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9FDEF8996
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B99420D165
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B986957E8B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE peres
        SQL);
    }
}
