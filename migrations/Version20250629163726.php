<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629163726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cercles (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_45C1718D8947610D (designation), INDEX IDX_45C1718D98260155 (region_id), INDEX IDX_45C1718DB03A8386 (created_by_id), INDEX IDX_45C1718D896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT NOT NULL, niveau_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(75) NOT NULL, capacite INT NOT NULL, effectif INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_2ED7EC5FF631228 (etablissement_id), INDEX IDX_2ED7EC5B3E9C81 (niveau_id), INDEX IDX_2ED7EC5B03A8386 (created_by_id), INDEX IDX_2ED7EC5896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE communes (id INT AUTO_INCREMENT NOT NULL, cercle_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_5C5EE2A527413AB9 (cercle_id), INDEX IDX_5C5EE2A5B03A8386 (created_by_id), INDEX IDX_5C5EE2A5896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cycles (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, effectif INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_72B88B248947610D (designation), INDEX IDX_72B88B24B03A8386 (created_by_id), INDEX IDX_72B88B24896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cycles_enseignements (cycles_id INT NOT NULL, enseignements_id INT NOT NULL, INDEX IDX_61407BF144C85140 (cycles_id), INDEX IDX_61407BF1DCB471D6 (enseignements_id), PRIMARY KEY(cycles_id, enseignements_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE eleves (id INT AUTO_INCREMENT NOT NULL, nom_id INT NOT NULL, prenom_id INT NOT NULL, lieu_naissance_id INT NOT NULL, parent_id INT NOT NULL, etablissement_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, user_id INT DEFAULT NULL, statut_id INT NOT NULL, scolarite1_id INT DEFAULT NULL, scolarite2_id INT DEFAULT NULL, redoublement1_id INT DEFAULT NULL, redoublement2_id INT DEFAULT NULL, redoublement3_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, sexe VARCHAR(1) NOT NULL, date_naissance DATE NOT NULL COMMENT '(DC2Type:date_immutable)', date_acte DATE NOT NULL COMMENT '(DC2Type:date_immutable)', numero_acte VARCHAR(30) NOT NULL, email VARCHAR(255) DEFAULT NULL, niveau VARCHAR(255) DEFAULT NULL, is_actif TINYINT(1) NOT NULL, is_admis TINYINT(1) NOT NULL, is_allowed TINYINT(1) NOT NULL, fullname VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_383B09B1C8121CE9 (nom_id), INDEX IDX_383B09B158819F9E (prenom_id), INDEX IDX_383B09B138C8067D (lieu_naissance_id), INDEX IDX_383B09B1727ACA70 (parent_id), INDEX IDX_383B09B1FF631228 (etablissement_id), INDEX IDX_383B09B18F5EA509 (classe_id), UNIQUE INDEX UNIQ_383B09B1A76ED395 (user_id), INDEX IDX_383B09B1F6203804 (statut_id), INDEX IDX_383B09B1F4C45000 (scolarite1_id), INDEX IDX_383B09B1E671FFEE (scolarite2_id), INDEX IDX_383B09B16D13ADFD (redoublement1_id), INDEX IDX_383B09B17FA60213 (redoublement2_id), INDEX IDX_383B09B1C71A6576 (redoublement3_id), INDEX IDX_383B09B1B03A8386 (created_by_id), INDEX IDX_383B09B1896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE enseignements (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_89D792808947610D (designation), INDEX IDX_89D79280B03A8386 (created_by_id), INDEX IDX_89D79280896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE enseignements_statuts (enseignements_id INT NOT NULL, statuts_id INT NOT NULL, INDEX IDX_ACDCF820DCB471D6 (enseignements_id), INDEX IDX_ACDCF820E0EA5904 (statuts_id), PRIMARY KEY(enseignements_id, statuts_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE etablissements (id INT AUTO_INCREMENT NOT NULL, enseignement_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(100) NOT NULL, forme_juridique VARCHAR(30) NOT NULL, decision_creation VARCHAR(30) NOT NULL, decision_ouverture VARCHAR(30) DEFAULT NULL, date_ouverture DATE DEFAULT NULL COMMENT '(DC2Type:date_immutable)', numero_social VARCHAR(30) DEFAULT NULL, numero_fiscal VARCHAR(30) DEFAULT NULL, compte_bancaire VARCHAR(60) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(23) NOT NULL, telephone_mobile VARCHAR(25) DEFAULT NULL, email VARCHAR(100) NOT NULL, capacite INT DEFAULT NULL, effectif INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_29F65EB18947610D (designation), UNIQUE INDEX UNIQ_29F65EB1DA324A4A (decision_creation), UNIQUE INDEX UNIQ_29F65EB182AADFCF (decision_ouverture), INDEX IDX_29F65EB1ABEC3B20 (enseignement_id), INDEX IDX_29F65EB1B03A8386 (created_by_id), INDEX IDX_29F65EB1896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE etablissements_users (etablissements_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_5C9FCC1BD23B76CD (etablissements_id), INDEX IDX_5C9FCC1B67B3B43D (users_id), PRIMARY KEY(etablissements_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lieu_naissances (id INT AUTO_INCREMENT NOT NULL, commune_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(75) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_49F8927F131A4F72 (commune_id), INDEX IDX_49F8927FB03A8386 (created_by_id), INDEX IDX_49F8927F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE meres (id INT AUTO_INCREMENT NOT NULL, nom_id INT NOT NULL, prenom_id INT NOT NULL, profession_id INT NOT NULL, telephone1_id INT NOT NULL, nina_id INT DEFAULT NULL, telephone2_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, fullname VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_2D8B408AC8121CE9 (nom_id), INDEX IDX_2D8B408A58819F9E (prenom_id), INDEX IDX_2D8B408AFDEF8996 (profession_id), UNIQUE INDEX UNIQ_2D8B408A9420D165 (telephone1_id), UNIQUE INDEX UNIQ_2D8B408A5586F33C (nina_id), INDEX IDX_2D8B408A86957E8B (telephone2_id), INDEX IDX_2D8B408AB03A8386 (created_by_id), INDEX IDX_2D8B408A896DBBDE (updated_by_id), INDEX IDX_MERES_FULLNAME (fullname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ninas (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, numero VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_51AD1AF2B03A8386 (created_by_id), INDEX IDX_51AD1AF2896DBBDE (updated_by_id), INDEX IDX_NINAS_NUMERO (numero), UNIQUE INDEX UNIQ_NINAS_NUMERO (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE niveaux (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, effectif INT NOT NULL, age_min INT DEFAULT NULL, age_max INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_56F771A08947610D (designation), INDEX IDX_56F771A05EC1162 (cycle_id), INDEX IDX_56F771A0B03A8386 (created_by_id), INDEX IDX_56F771A0896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE noms (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_A069E65DB03A8386 (created_by_id), INDEX IDX_A069E65D896DBBDE (updated_by_id), INDEX IDX_NOMS_DESIGNATION (designation), UNIQUE INDEX UNIQ_NOMS_DESIGNATION (designation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parents (id INT AUTO_INCREMENT NOT NULL, pere_id INT NOT NULL, mere_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, fullname VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_FD501D6A3FD73900 (pere_id), INDEX IDX_FD501D6A39DEC40E (mere_id), INDEX IDX_FD501D6AB03A8386 (created_by_id), INDEX IDX_FD501D6A896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE peres (id INT AUTO_INCREMENT NOT NULL, nom_id INT NOT NULL, prenom_id INT NOT NULL, profession_id INT NOT NULL, telephone1_id INT NOT NULL, nina_id INT DEFAULT NULL, telephone2_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, fullname VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_B5FB13B9C8121CE9 (nom_id), INDEX IDX_B5FB13B958819F9E (prenom_id), INDEX IDX_B5FB13B9FDEF8996 (profession_id), UNIQUE INDEX UNIQ_B5FB13B99420D165 (telephone1_id), UNIQUE INDEX UNIQ_B5FB13B95586F33C (nina_id), INDEX IDX_B5FB13B986957E8B (telephone2_id), INDEX IDX_B5FB13B9B03A8386 (created_by_id), INDEX IDX_B5FB13B9896DBBDE (updated_by_id), INDEX IDX_PERES_FULLNAME (fullname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE prenoms (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(75) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_E71162E3B03A8386 (created_by_id), INDEX IDX_E71162E3896DBBDE (updated_by_id), INDEX IDX_PRENOMS_DESIGNATION (designation), UNIQUE INDEX UNIQ_PRENOMS_DESIGNATION (designation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE professions (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(130) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_2FDA85FAB03A8386 (created_by_id), INDEX IDX_2FDA85FA896DBBDE (updated_by_id), INDEX IDX_PROFESSIONS_DESIGNATION (designation), UNIQUE INDEX UNIQ_PROFESSIONS_DESIGNATION (designation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE redoublements1 (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, niveau_id INT NOT NULL, scolarite1_id INT NOT NULL, scolarite2_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(75) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_2554EDA95EC1162 (cycle_id), INDEX IDX_2554EDA9B3E9C81 (niveau_id), INDEX IDX_2554EDA9F4C45000 (scolarite1_id), INDEX IDX_2554EDA9E671FFEE (scolarite2_id), INDEX IDX_2554EDA9B03A8386 (created_by_id), INDEX IDX_2554EDA9896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE redoublements2 (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, niveau_id INT NOT NULL, scolarite1_id INT NOT NULL, scolarite2_id INT NOT NULL, redoublement1_id INT NOT NULL, designation VARCHAR(75) NOT NULL, INDEX IDX_BC5DBC135EC1162 (cycle_id), INDEX IDX_BC5DBC13B3E9C81 (niveau_id), INDEX IDX_BC5DBC13F4C45000 (scolarite1_id), INDEX IDX_BC5DBC13E671FFEE (scolarite2_id), INDEX IDX_BC5DBC136D13ADFD (redoublement1_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE redoublements3 (id INT AUTO_INCREMENT NOT NULL, cycle_id INT NOT NULL, niveau_id INT NOT NULL, scolarite1_id INT NOT NULL, scolarite2_id INT NOT NULL, redoublement2_id INT NOT NULL, designation VARCHAR(75) NOT NULL, INDEX IDX_CB5A8C855EC1162 (cycle_id), INDEX IDX_CB5A8C85B3E9C81 (niveau_id), INDEX IDX_CB5A8C85F4C45000 (scolarite1_id), INDEX IDX_CB5A8C85E671FFEE (scolarite2_id), INDEX IDX_CB5A8C857FA60213 (redoublement2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE regions (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_A26779F38947610D (designation), INDEX IDX_A26779F3B03A8386 (created_by_id), INDEX IDX_A26779F3896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE scolarites1 (id INT AUTO_INCREMENT NOT NULL, niveau_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, scolarite INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_328D2B44B3E9C81 (niveau_id), INDEX IDX_328D2B44B03A8386 (created_by_id), INDEX IDX_328D2B44896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE scolarites2 (id INT AUTO_INCREMENT NOT NULL, scolarite1_id INT NOT NULL, niveau_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, scolarite INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_AB847AFEF4C45000 (scolarite1_id), INDEX IDX_AB847AFEB3E9C81 (niveau_id), INDEX IDX_AB847AFEB03A8386 (created_by_id), INDEX IDX_AB847AFE896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE statuts (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation VARCHAR(60) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_403505E68947610D (designation), INDEX IDX_403505E6B03A8386 (created_by_id), INDEX IDX_403505E6896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE telephone1 (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, numero VARCHAR(23) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_9E2EF023B03A8386 (created_by_id), INDEX IDX_9E2EF023896DBBDE (updated_by_id), INDEX IDX_TELEPHONES_1_NUMERO (numero), UNIQUE INDEX UNIQ_TELEPHONES_1_NUMERO (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE telephone2 (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, numero VARCHAR(23) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_727A199B03A8386 (created_by_id), INDEX IDX_727A199896DBBDE (updated_by_id), INDEX IDX_TELEPHONES_2_NUMERO (numero), UNIQUE INDEX UNIQ_TELEPHONES_2_NUMERO (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, pere_id INT DEFAULT NULL, mere_id INT DEFAULT NULL, etablissement_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(60) NOT NULL, prenom VARCHAR(75) NOT NULL, email VARCHAR(255) NOT NULL, is_actif TINYINT(1) NOT NULL, is_allowed TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(128) NOT NULL, INDEX IDX_1483A5E93FD73900 (pere_id), INDEX IDX_1483A5E939DEC40E (mere_id), INDEX IDX_1483A5E9FF631228 (etablissement_id), INDEX IDX_1483A5E9B03A8386 (created_by_id), INDEX IDX_1483A5E9896DBBDE (updated_by_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles ADD CONSTRAINT FK_45C1718D98260155 FOREIGN KEY (region_id) REFERENCES regions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles ADD CONSTRAINT FK_45C1718DB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles ADD CONSTRAINT FK_45C1718D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC5FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissements (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC5B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC5B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC5896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A527413AB9 FOREIGN KEY (cercle_id) REFERENCES cercles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A5B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A5896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles ADD CONSTRAINT FK_72B88B24B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles ADD CONSTRAINT FK_72B88B24896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements ADD CONSTRAINT FK_61407BF144C85140 FOREIGN KEY (cycles_id) REFERENCES cycles (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements ADD CONSTRAINT FK_61407BF1DCB471D6 FOREIGN KEY (enseignements_id) REFERENCES enseignements (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1C8121CE9 FOREIGN KEY (nom_id) REFERENCES noms (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B158819F9E FOREIGN KEY (prenom_id) REFERENCES prenoms (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B138C8067D FOREIGN KEY (lieu_naissance_id) REFERENCES lieu_naissances (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1727ACA70 FOREIGN KEY (parent_id) REFERENCES parents (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissements (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B18F5EA509 FOREIGN KEY (classe_id) REFERENCES classes (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1F6203804 FOREIGN KEY (statut_id) REFERENCES statuts (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1F4C45000 FOREIGN KEY (scolarite1_id) REFERENCES scolarites1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1E671FFEE FOREIGN KEY (scolarite2_id) REFERENCES scolarites2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B16D13ADFD FOREIGN KEY (redoublement1_id) REFERENCES redoublements1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B17FA60213 FOREIGN KEY (redoublement2_id) REFERENCES redoublements2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1C71A6576 FOREIGN KEY (redoublement3_id) REFERENCES redoublements3 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves ADD CONSTRAINT FK_383B09B1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements ADD CONSTRAINT FK_89D79280B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements ADD CONSTRAINT FK_89D79280896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts ADD CONSTRAINT FK_ACDCF820DCB471D6 FOREIGN KEY (enseignements_id) REFERENCES enseignements (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts ADD CONSTRAINT FK_ACDCF820E0EA5904 FOREIGN KEY (statuts_id) REFERENCES statuts (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB1ABEC3B20 FOREIGN KEY (enseignement_id) REFERENCES enseignements (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB1B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users ADD CONSTRAINT FK_5C9FCC1BD23B76CD FOREIGN KEY (etablissements_id) REFERENCES etablissements (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users ADD CONSTRAINT FK_5C9FCC1B67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances ADD CONSTRAINT FK_49F8927F131A4F72 FOREIGN KEY (commune_id) REFERENCES communes (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances ADD CONSTRAINT FK_49F8927FB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances ADD CONSTRAINT FK_49F8927F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408AC8121CE9 FOREIGN KEY (nom_id) REFERENCES noms (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A58819F9E FOREIGN KEY (prenom_id) REFERENCES prenoms (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408AFDEF8996 FOREIGN KEY (profession_id) REFERENCES professions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A9420D165 FOREIGN KEY (telephone1_id) REFERENCES telephone1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A5586F33C FOREIGN KEY (nina_id) REFERENCES ninas (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A86957E8B FOREIGN KEY (telephone2_id) REFERENCES telephone2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres ADD CONSTRAINT FK_2D8B408A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ninas ADD CONSTRAINT FK_51AD1AF2B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ninas ADD CONSTRAINT FK_51AD1AF2896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveaux ADD CONSTRAINT FK_56F771A05EC1162 FOREIGN KEY (cycle_id) REFERENCES cycles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveaux ADD CONSTRAINT FK_56F771A0B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveaux ADD CONSTRAINT FK_56F771A0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms ADD CONSTRAINT FK_A069E65DB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms ADD CONSTRAINT FK_A069E65D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents ADD CONSTRAINT FK_FD501D6A3FD73900 FOREIGN KEY (pere_id) REFERENCES peres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents ADD CONSTRAINT FK_FD501D6A39DEC40E FOREIGN KEY (mere_id) REFERENCES meres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents ADD CONSTRAINT FK_FD501D6AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents ADD CONSTRAINT FK_FD501D6A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
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
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B95586F33C FOREIGN KEY (nina_id) REFERENCES ninas (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B986957E8B FOREIGN KEY (telephone2_id) REFERENCES telephone2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres ADD CONSTRAINT FK_B5FB13B9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms ADD CONSTRAINT FK_E71162E3B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms ADD CONSTRAINT FK_E71162E3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions ADD CONSTRAINT FK_2FDA85FAB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions ADD CONSTRAINT FK_2FDA85FA896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
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
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 ADD CONSTRAINT FK_2554EDA9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
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
            ALTER TABLE redoublements3 ADD CONSTRAINT FK_CB5A8C857FA60213 FOREIGN KEY (redoublement2_id) REFERENCES redoublements2 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE regions ADD CONSTRAINT FK_A26779F3B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE regions ADD CONSTRAINT FK_A26779F3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites1 ADD CONSTRAINT FK_328D2B44B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites1 ADD CONSTRAINT FK_328D2B44B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites1 ADD CONSTRAINT FK_328D2B44896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 ADD CONSTRAINT FK_AB847AFEF4C45000 FOREIGN KEY (scolarite1_id) REFERENCES scolarites1 (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 ADD CONSTRAINT FK_AB847AFEB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 ADD CONSTRAINT FK_AB847AFEB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 ADD CONSTRAINT FK_AB847AFE896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statuts ADD CONSTRAINT FK_403505E6B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statuts ADD CONSTRAINT FK_403505E6896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 ADD CONSTRAINT FK_9E2EF023B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 ADD CONSTRAINT FK_9E2EF023896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 ADD CONSTRAINT FK_727A199B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 ADD CONSTRAINT FK_727A199896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E93FD73900 FOREIGN KEY (pere_id) REFERENCES peres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E939DEC40E FOREIGN KEY (mere_id) REFERENCES meres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissements (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles DROP FOREIGN KEY FK_45C1718D98260155
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles DROP FOREIGN KEY FK_45C1718DB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cercles DROP FOREIGN KEY FK_45C1718D896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC5FF631228
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC5B3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC5B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC5896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes DROP FOREIGN KEY FK_5C5EE2A527413AB9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes DROP FOREIGN KEY FK_5C5EE2A5B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communes DROP FOREIGN KEY FK_5C5EE2A5896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles DROP FOREIGN KEY FK_72B88B24B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles DROP FOREIGN KEY FK_72B88B24896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements DROP FOREIGN KEY FK_61407BF144C85140
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cycles_enseignements DROP FOREIGN KEY FK_61407BF1DCB471D6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1C8121CE9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B158819F9E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B138C8067D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1FF631228
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B18F5EA509
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1F6203804
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1F4C45000
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1E671FFEE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B16D13ADFD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B17FA60213
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1C71A6576
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B1896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements DROP FOREIGN KEY FK_89D79280B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements DROP FOREIGN KEY FK_89D79280896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts DROP FOREIGN KEY FK_ACDCF820DCB471D6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enseignements_statuts DROP FOREIGN KEY FK_ACDCF820E0EA5904
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB1ABEC3B20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB1B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB1896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users DROP FOREIGN KEY FK_5C9FCC1BD23B76CD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etablissements_users DROP FOREIGN KEY FK_5C9FCC1B67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances DROP FOREIGN KEY FK_49F8927F131A4F72
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances DROP FOREIGN KEY FK_49F8927FB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lieu_naissances DROP FOREIGN KEY FK_49F8927F896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408AC8121CE9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A58819F9E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408AFDEF8996
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A9420D165
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A5586F33C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A86957E8B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408AB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meres DROP FOREIGN KEY FK_2D8B408A896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ninas DROP FOREIGN KEY FK_51AD1AF2B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ninas DROP FOREIGN KEY FK_51AD1AF2896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveaux DROP FOREIGN KEY FK_56F771A05EC1162
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveaux DROP FOREIGN KEY FK_56F771A0B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE niveaux DROP FOREIGN KEY FK_56F771A0896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms DROP FOREIGN KEY FK_A069E65DB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE noms DROP FOREIGN KEY FK_A069E65D896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents DROP FOREIGN KEY FK_FD501D6A3FD73900
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents DROP FOREIGN KEY FK_FD501D6A39DEC40E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents DROP FOREIGN KEY FK_FD501D6AB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parents DROP FOREIGN KEY FK_FD501D6A896DBBDE
        SQL);
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
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B95586F33C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B986957E8B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peres DROP FOREIGN KEY FK_B5FB13B9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms DROP FOREIGN KEY FK_E71162E3B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prenoms DROP FOREIGN KEY FK_E71162E3896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions DROP FOREIGN KEY FK_2FDA85FAB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE professions DROP FOREIGN KEY FK_2FDA85FA896DBBDE
        SQL);
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
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE redoublements1 DROP FOREIGN KEY FK_2554EDA9896DBBDE
        SQL);
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
            ALTER TABLE redoublements3 DROP FOREIGN KEY FK_CB5A8C857FA60213
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE regions DROP FOREIGN KEY FK_A26779F3B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE regions DROP FOREIGN KEY FK_A26779F3896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites1 DROP FOREIGN KEY FK_328D2B44B3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites1 DROP FOREIGN KEY FK_328D2B44B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites1 DROP FOREIGN KEY FK_328D2B44896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 DROP FOREIGN KEY FK_AB847AFEF4C45000
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 DROP FOREIGN KEY FK_AB847AFEB3E9C81
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 DROP FOREIGN KEY FK_AB847AFEB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE scolarites2 DROP FOREIGN KEY FK_AB847AFE896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statuts DROP FOREIGN KEY FK_403505E6B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statuts DROP FOREIGN KEY FK_403505E6896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 DROP FOREIGN KEY FK_9E2EF023B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone1 DROP FOREIGN KEY FK_9E2EF023896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 DROP FOREIGN KEY FK_727A199B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE telephone2 DROP FOREIGN KEY FK_727A199896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E93FD73900
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E939DEC40E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FF631228
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9896DBBDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cercles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE classes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE communes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cycles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cycles_enseignements
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE eleves
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enseignements
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enseignements_statuts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE etablissements
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE etablissements_users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lieu_naissances
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE meres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ninas
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE niveaux
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE noms
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parents
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE peres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE prenoms
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE professions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE redoublements1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE redoublements2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE redoublements3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE regions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE scolarites1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE scolarites2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE statuts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE telephone1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE telephone2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
