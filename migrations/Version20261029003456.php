<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261029003456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campagne (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, exercice_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, CONSTRAINT FK_539B5D1689D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_539B5D1689D40298 ON campagne (exercice_id)');
        $this->addSql('CREATE TABLE exercice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, date_limite_prets DATE NOT NULL, retribution DOUBLE PRECISION NOT NULL, statut VARCHAR(20) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, groupement_id INTEGER NOT NULL, CONSTRAINT FK_E418C74DE66695CE FOREIGN KEY (groupement_id) REFERENCES groupement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E418C74DE66695CE ON exercice (groupement_id)');
        $this->addSql('CREATE TABLE groupement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, tel VARCHAR(30) NOT NULL, adresse VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE membre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, tel VARCHAR(30) NOT NULL, adresse VARCHAR(255) NOT NULL, login VARCHAR(100) NOT NULL, fonction VARCHAR(100) NOT NULL, role_id INTEGER NOT NULL, groupement_id INTEGER NOT NULL, CONSTRAINT FK_F6B4FB29D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F6B4FB29E66695CE FOREIGN KEY (groupement_id) REFERENCES groupement (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F6B4FB29D60322AC ON membre (role_id)');
        $this->addSql('CREATE INDEX IDX_F6B4FB29E66695CE ON membre (groupement_id)');
        $this->addSql('CREATE TABLE mode_cotisation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, is_libre BOOLEAN NOT NULL, membre_id INTEGER NOT NULL, campagne_id INTEGER NOT NULL, CONSTRAINT FK_6BB9377E6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6BB9377E16227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6BB9377E6A99F74A ON mode_cotisation (membre_id)');
        $this->addSql('CREATE INDEX IDX_6BB9377E16227374 ON mode_cotisation (campagne_id)');
        $this->addSql('CREATE TABLE operation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, membre_id INTEGER NOT NULL, seance_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, motif VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_1981A66D6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1981A66DE3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1981A66D6A99F74A ON operation (membre_id)');
        $this->addSql('CREATE INDEX IDX_1981A66DE3797A94 ON operation (seance_id)');
        $this->addSql('CREATE TABLE pret (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, duree_en_mois INTEGER NOT NULL, date_debut DATE NOT NULL, membre_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, taux_interet DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_52ECE9796A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_52ECE97989D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_52ECE9796A99F74A ON pret (membre_id)');
        $this->addSql('CREATE INDEX IDX_52ECE97989D40298 ON pret (exercice_id)');
        $this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom_role VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE seance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero INTEGER NOT NULL, date_seance DATE NOT NULL, campagne_id INTEGER NOT NULL, CONSTRAINT FK_DF7DFD0E16227374 FOREIGN KEY (campagne_id) REFERENCES campagne (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0E16227374 ON seance (campagne_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE campagne');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE groupement');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE mode_cotisation');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE pret');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
