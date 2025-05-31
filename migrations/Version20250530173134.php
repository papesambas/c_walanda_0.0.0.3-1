<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530173134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE etablissements_users (etablissements_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_5C9FCC1BD23B76CD (etablissements_id), INDEX IDX_5C9FCC1B67B3B43D (users_id), PRIMARY KEY(etablissements_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users ADD CONSTRAINT FK_5C9FCC1BD23B76CD FOREIGN KEY (etablissements_id) REFERENCES etablissements (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users ADD CONSTRAINT FK_5C9FCC1B67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD etablissement_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissements (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1483A5E9FF631228 ON users (etablissement_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users DROP FOREIGN KEY FK_5C9FCC1BD23B76CD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users DROP FOREIGN KEY FK_5C9FCC1B67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE etablissements_users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FF631228
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1483A5E9FF631228 ON users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP etablissement_id
        SQL);
    }
}
