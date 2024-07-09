<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709102027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, cin BIGINT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, gender VARCHAR(255) NOT NULL, driver_license TINYINT(1) DEFAULT NULL, role VARCHAR(255) NOT NULL, photo_adress VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_8D93D649F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('DROP TABLE participant');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE trajet ADD debut_id INT NOT NULL, ADD destination_id INT NOT NULL, ADD seats_available INT DEFAULT NULL, ADD seats_occupied INT DEFAULT NULL, ADD price INT DEFAULT NULL, DROP begin, DROP destination');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CE15EF86D FOREIGN KEY (debut_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98C816C6140 FOREIGN KEY (destination_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_2B5BA98CE15EF86D ON trajet (debut_id)');
        $this->addSql('CREATE INDEX IDX_2B5BA98C816C6140 ON trajet (destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81D12A823');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CE15EF86D');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98C816C6140');
        $this->addSql('DROP INDEX IDX_2B5BA98CE15EF86D ON trajet');
        $this->addSql('DROP INDEX IDX_2B5BA98C816C6140 ON trajet');
        $this->addSql('ALTER TABLE trajet ADD begin LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', ADD destination LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', DROP debut_id, DROP destination_id, DROP seats_available, DROP seats_occupied, DROP price');
    }
}
