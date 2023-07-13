<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713084818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_subscription (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, subscription_id_id INT DEFAULT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, INDEX IDX_5D0BAE1D38B53C32 (company_id_id), INDEX IDX_5D0BAE1D857C9F24 (subscription_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, criteria_dept INT NOT NULL, criteria_user INT NOT NULL, criteria_storage INT NOT NULL, duration VARCHAR(30) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_company (subscription_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_3CAAE9D49A1887DC (subscription_id), INDEX IDX_3CAAE9D4979B1AD6 (company_id), PRIMARY KEY(subscription_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_subscription ADD CONSTRAINT FK_5D0BAE1D38B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company_subscription ADD CONSTRAINT FK_5D0BAE1D857C9F24 FOREIGN KEY (subscription_id_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE subscription_company ADD CONSTRAINT FK_3CAAE9D49A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_company ADD CONSTRAINT FK_3CAAE9D4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_subscription DROP FOREIGN KEY FK_5D0BAE1D38B53C32');
        $this->addSql('ALTER TABLE company_subscription DROP FOREIGN KEY FK_5D0BAE1D857C9F24');
        $this->addSql('ALTER TABLE subscription_company DROP FOREIGN KEY FK_3CAAE9D49A1887DC');
        $this->addSql('ALTER TABLE subscription_company DROP FOREIGN KEY FK_3CAAE9D4979B1AD6');
        $this->addSql('DROP TABLE company_subscription');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE subscription_company');
    }
}
