<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228181742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, reset_token VARCHAR(60) DEFAULT NULL, last_login_date DATETIME NOT NULL, disable_token VARCHAR(65) DEFAULT NULL, phone_number INT NOT NULL, verification_code VARCHAR(255) DEFAULT NULL, usertag VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie ADD id INT AUTO_INCREMENT NOT NULL, DROP id_ca, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE equipement ADD category_id_id INT DEFAULT NULL, CHANGE image_eq image_eq VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F39777D11E FOREIGN KEY (category_id_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_B8B4C6F39777D11E ON equipement (category_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE categorie MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE categorie DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE categorie ADD id_ca INT NOT NULL, DROP id');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F39777D11E');
        $this->addSql('DROP INDEX IDX_B8B4C6F39777D11E ON equipement');
        $this->addSql('ALTER TABLE equipement DROP category_id_id, CHANGE image_eq image_eq LONGBLOB NOT NULL');
    }
}
