<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031005238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ALTER surname TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE client ALTER telephone TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553256915B FOREIGN KEY (relation_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404553256915B ON client (relation_id)');
        $this->addSql('ALTER TABLE dette ADD statut BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE dette ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE dette ALTER date SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN dette.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ALTER surname SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER surname TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6493256915B FOREIGN KEY (relation_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493256915B ON "user" (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6493256915B');
        $this->addSql('DROP INDEX UNIQ_8D93D6493256915B');
        $this->addSql('ALTER TABLE "user" ALTER surname DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER surname TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C74404553256915B');
        $this->addSql('DROP INDEX UNIQ_C74404553256915B');
        $this->addSql('ALTER TABLE client ALTER surname TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE client ALTER telephone TYPE VARCHAR(9)');
        $this->addSql('ALTER TABLE dette DROP statut');
        $this->addSql('ALTER TABLE dette ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE dette ALTER date DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN dette.date IS NULL');
    }
}
