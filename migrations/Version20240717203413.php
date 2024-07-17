<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717203413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD commenter_id_id INT NOT NULL, ADD commented_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C455BE0B1 FOREIGN KEY (commenter_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C41554945 FOREIGN KEY (commented_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526C455BE0B1 ON comment (commenter_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526C41554945 ON comment (commented_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C455BE0B1');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C41554945');
        $this->addSql('DROP INDEX IDX_9474526C455BE0B1 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C41554945 ON comment');
        $this->addSql('ALTER TABLE comment DROP commenter_id_id, DROP commented_id_id');
    }
}
