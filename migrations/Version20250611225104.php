<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611225104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B15EA22A61
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_383B09B15EA22A61 ON eleves
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves CHANGE scolarites3_id scolarite3_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B15ECD988B FOREIGN KEY (scolarite3_id) REFERENCES scolarites3 (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_383B09B15ECD988B ON eleves (scolarite3_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B15ECD988B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_383B09B15ECD988B ON eleves
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves CHANGE scolarite3_id scolarites3_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B15EA22A61 FOREIGN KEY (scolarites3_id) REFERENCES scolarites3 (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_383B09B15EA22A61 ON eleves (scolarites3_id)
        SQL);
    }
}
