<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717104146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription_company DROP FOREIGN KEY FK_3CAAE9D4979B1AD6');
        $this->addSql('ALTER TABLE subscription_company DROP FOREIGN KEY FK_3CAAE9D49A1887DC');
        $this->addSql('DROP TABLE subscription_company');
        $this->addSql('ALTER TABLE transaction ADD amount INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_company (subscription_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_3CAAE9D49A1887DC (subscription_id), INDEX IDX_3CAAE9D4979B1AD6 (company_id), PRIMARY KEY(subscription_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subscription_company ADD CONSTRAINT FK_3CAAE9D4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_company ADD CONSTRAINT FK_3CAAE9D49A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction DROP amount');
    }
}
