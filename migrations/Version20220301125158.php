<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301125158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, sponsors_id_id INT DEFAULT NULL, nom_ev VARCHAR(255) NOT NULL, date_ev DATE NOT NULL, heured_ev TIME NOT NULL, heuref_ev TIME NOT NULL, INDEX IDX_B26681E4CCA2247 (sponsors_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, nom_sp VARCHAR(255) NOT NULL, num_sp INT NOT NULL, email_sp VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E4CCA2247 FOREIGN KEY (sponsors_id_id) REFERENCES sponsor (id)');
        $this->addSql('ALTER TABLE user DROP activation_token');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E4CCA2247');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('ALTER TABLE user ADD activation_token VARCHAR(50) DEFAULT NULL');
    }
}
