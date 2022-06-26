<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625161315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account (accountNumber VARCHAR(11) NOT NULL, account_type VARCHAR(255) NOT NULL, account_name VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, socialSecurityNumber VARCHAR(255) DEFAULT NULL, PRIMARY KEY(accountNumber))');
        $this->addSql('CREATE INDEX IDX_53A23E0A2336A336 ON bank_account (socialSecurityNumber)');
        $this->addSql('CREATE TABLE customer (socialSecurityNumber VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(socialSecurityNumber))');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A2336A336 FOREIGN KEY (socialSecurityNumber) REFERENCES customer (socialSecurityNumber) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bank_account DROP CONSTRAINT FK_53A23E0A2336A336');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE customer');
    }
}
