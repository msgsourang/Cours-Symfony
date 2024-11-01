<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028153348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ALTER surname TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE dette ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE dette ALTER date SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN dette.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ALTER nom TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(35)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER nom TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE dette ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE dette ALTER date DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN dette.date IS NULL');
        $this->addSql('ALTER TABLE client ALTER surname TYPE VARCHAR(20)');
    }
}
