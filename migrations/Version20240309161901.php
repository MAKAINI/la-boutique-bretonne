<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309161901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE header_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE header (id INT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, btn_title VARCHAR(255) NOT NULL, btn_url VARCHAR(255) NOT NULL, illustration VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE product ALTER is_best DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE header_id_seq CASCADE');
        $this->addSql('DROP TABLE header');
        $this->addSql('ALTER TABLE product ALTER is_best SET DEFAULT false');
    }
}
