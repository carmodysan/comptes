<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021162055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monthly_account (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, month INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, monthly_account_id INT NOT NULL, date_op DATE NOT NULL, category VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, credit DOUBLE PRECISION DEFAULT NULL, debit DOUBLE PRECISION DEFAULT NULL, checked TINYINT(1) NOT NULL, author VARCHAR(255) NOT NULL, INDEX IDX_1981A66D52763F3F (monthly_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D52763F3F FOREIGN KEY (monthly_account_id) REFERENCES monthly_account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D52763F3F');
        $this->addSql('DROP TABLE monthly_account');
        $this->addSql('DROP TABLE operation');
    }
}
