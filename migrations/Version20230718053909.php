<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718053909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bills (id INT AUTO_INCREMENT NOT NULL, project_id_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, amount INT NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_22775DD06C1197C9 (project_id_id), INDEX IDX_22775DD038248176 (currency_id), INDEX IDX_22775DD0B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(40) NOT NULL, email VARCHAR(60) NOT NULL, about LONGTEXT NOT NULL, is_approved SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C744045538B53C32 (company_id_id), INDEX IDX_C7440455B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(40) NOT NULL, about LONGTEXT NOT NULL, established_at DATE NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4FBF094FB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_subscription (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, subscription_id_id INT DEFAULT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5D0BAE1D38B53C32 (company_id_id), INDEX IDX_5D0BAE1D857C9F24 (subscription_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, contact_no VARCHAR(10) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(90) NOT NULL, state VARCHAR(90) NOT NULL, pin_code VARCHAR(6) NOT NULL, country VARCHAR(90) NOT NULL, is_deleted TINYINT(1) NOT NULL, usertype VARCHAR(255) NOT NULL, reference_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(20) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, is_deleted TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CD1DE18A38B53C32 (company_id_id), INDEX IDX_CD1DE18AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, modes_of_conversation_id INT DEFAULT NULL, filename VARCHAR(100) NOT NULL, path VARCHAR(255) NOT NULL, reference_type VARCHAR(255) NOT NULL, reference_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A2B07288E0A77E0E (modes_of_conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_skills (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, skill_id_id INT DEFAULT NULL, level VARCHAR(255) NOT NULL, INDEX IDX_FC00D2E59D86650F (user_id_id), INDEX IDX_FC00D2E55A6C0D6B (skill_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modes_of_conversation (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9427E72EB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password_reset_request (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C5D0A95A9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, client_id_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, title VARCHAR(90) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, start_date DATE NOT NULL, expected_end_date DATE NOT NULL, payment_type VARCHAR(255) NOT NULL, amc_support DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2FB3D0EEDC2902E0 (client_id_id), INDEX IDX_2FB3D0EEB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, from_id_id INT DEFAULT NULL, task_id_id INT DEFAULT NULL, forward_to_id INT DEFAULT NULL, reason LONGTEXT NOT NULL, is_approved TINYINT(1) NOT NULL, request_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', approved_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3B978F9F38B53C32 (company_id_id), INDEX IDX_3B978F9F4632BB48 (from_id_id), INDEX IDX_3B978F9FB8E08577 (task_id_id), INDEX IDX_3B978F9F9824A90D (forward_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(40) NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D5311670B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, criteria_dept INT NOT NULL, criteria_user INT NOT NULL, criteria_storage INT NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_company (subscription_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_3CAAE9D49A1887DC (subscription_id), INDEX IDX_3CAAE9D4979B1AD6 (company_id), PRIMARY KEY(subscription_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_duration (id INT AUTO_INCREMENT NOT NULL, subscription_id_id INT DEFAULT NULL, duration VARCHAR(30) DEFAULT NULL, price INT DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E1F1DFEC857C9F24 (subscription_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, project_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, priority VARCHAR(255) NOT NULL, severity VARCHAR(255) NOT NULL, title VARCHAR(90) NOT NULL, description LONGTEXT NOT NULL, time TIME NOT NULL, status VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_505865976C1197C9 (project_id_id), INDEX IDX_505865979D86650F (user_id_id), INDEX IDX_50586597B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_line (id INT AUTO_INCREMENT NOT NULL, client_id_id INT DEFAULT NULL, emp_id_id INT DEFAULT NULL, company_id_id INT DEFAULT NULL, mode_id INT DEFAULT NULL, subject VARCHAR(20) DEFAULT NULL, decription LONGTEXT NOT NULL, conclusion LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7CA9BDDBDC2902E0 (client_id_id), INDEX IDX_7CA9BDDB13C5666C (emp_id_id), INDEX IDX_7CA9BDDB38B53C32 (company_id_id), INDEX IDX_7CA9BDDB77E5854A (mode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeline_project (id INT AUTO_INCREMENT NOT NULL, timeline_id_id INT DEFAULT NULL, project_id_id INT DEFAULT NULL, INDEX IDX_1A99E79E5F2A1311 (timeline_id_id), INDEX IDX_1A99E79E6C1197C9 (project_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timesheets (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, project_id_id INT DEFAULT NULL, task_id_id INT DEFAULT NULL, hours_worked TIME NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9AC77D2E9D86650F (user_id_id), INDEX IDX_9AC77D2E6C1197C9 (project_id_id), INDEX IDX_9AC77D2EB8E08577 (task_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(40) NOT NULL, first_name VARCHAR(40) NOT NULL, last_name VARCHAR(40) NOT NULL, gender VARCHAR(255) NOT NULL, dob DATE NOT NULL, is_verified TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD06C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD038248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045538B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company_subscription ADD CONSTRAINT FK_5D0BAE1D38B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company_subscription ADD CONSTRAINT FK_5D0BAE1D857C9F24 FOREIGN KEY (subscription_id_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A38B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288E0A77E0E FOREIGN KEY (modes_of_conversation_id) REFERENCES modes_of_conversation (id)');
        $this->addSql('ALTER TABLE employee_skills ADD CONSTRAINT FK_FC00D2E59D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE employee_skills ADD CONSTRAINT FK_FC00D2E55A6C0D6B FOREIGN KEY (skill_id_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE modes_of_conversation ADD CONSTRAINT FK_9427E72EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE password_reset_request ADD CONSTRAINT FK_C5D0A95A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F38B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F4632BB48 FOREIGN KEY (from_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FB8E08577 FOREIGN KEY (task_id_id) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F9824A90D FOREIGN KEY (forward_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D5311670B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription_company ADD CONSTRAINT FK_3CAAE9D49A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_company ADD CONSTRAINT FK_3CAAE9D4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_duration ADD CONSTRAINT FK_E1F1DFEC857C9F24 FOREIGN KEY (subscription_id_id) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865976C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865979D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE time_line ADD CONSTRAINT FK_7CA9BDDBDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE time_line ADD CONSTRAINT FK_7CA9BDDB13C5666C FOREIGN KEY (emp_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE time_line ADD CONSTRAINT FK_7CA9BDDB38B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE time_line ADD CONSTRAINT FK_7CA9BDDB77E5854A FOREIGN KEY (mode_id) REFERENCES modes_of_conversation (id)');
        $this->addSql('ALTER TABLE timeline_project ADD CONSTRAINT FK_1A99E79E5F2A1311 FOREIGN KEY (timeline_id_id) REFERENCES time_line (id)');
        $this->addSql('ALTER TABLE timeline_project ADD CONSTRAINT FK_1A99E79E6C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE timesheets ADD CONSTRAINT FK_9AC77D2E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timesheets ADD CONSTRAINT FK_9AC77D2E6C1197C9 FOREIGN KEY (project_id_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE timesheets ADD CONSTRAINT FK_9AC77D2EB8E08577 FOREIGN KEY (task_id_id) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD06C1197C9');
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD038248176');
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD0B03A8386');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C744045538B53C32');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455B03A8386');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FB03A8386');
        $this->addSql('ALTER TABLE company_subscription DROP FOREIGN KEY FK_5D0BAE1D38B53C32');
        $this->addSql('ALTER TABLE company_subscription DROP FOREIGN KEY FK_5D0BAE1D857C9F24');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A38B53C32');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18AB03A8386');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288E0A77E0E');
        $this->addSql('ALTER TABLE employee_skills DROP FOREIGN KEY FK_FC00D2E59D86650F');
        $this->addSql('ALTER TABLE employee_skills DROP FOREIGN KEY FK_FC00D2E55A6C0D6B');
        $this->addSql('ALTER TABLE modes_of_conversation DROP FOREIGN KEY FK_9427E72EB03A8386');
        $this->addSql('ALTER TABLE password_reset_request DROP FOREIGN KEY FK_C5D0A95A9D86650F');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEDC2902E0');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB03A8386');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F38B53C32');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F4632BB48');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FB8E08577');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F9824A90D');
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D5311670B03A8386');
        $this->addSql('ALTER TABLE subscription_company DROP FOREIGN KEY FK_3CAAE9D49A1887DC');
        $this->addSql('ALTER TABLE subscription_company DROP FOREIGN KEY FK_3CAAE9D4979B1AD6');
        $this->addSql('ALTER TABLE subscription_duration DROP FOREIGN KEY FK_E1F1DFEC857C9F24');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865976C1197C9');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865979D86650F');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597B03A8386');
        $this->addSql('ALTER TABLE time_line DROP FOREIGN KEY FK_7CA9BDDBDC2902E0');
        $this->addSql('ALTER TABLE time_line DROP FOREIGN KEY FK_7CA9BDDB13C5666C');
        $this->addSql('ALTER TABLE time_line DROP FOREIGN KEY FK_7CA9BDDB38B53C32');
        $this->addSql('ALTER TABLE time_line DROP FOREIGN KEY FK_7CA9BDDB77E5854A');
        $this->addSql('ALTER TABLE timeline_project DROP FOREIGN KEY FK_1A99E79E5F2A1311');
        $this->addSql('ALTER TABLE timeline_project DROP FOREIGN KEY FK_1A99E79E6C1197C9');
        $this->addSql('ALTER TABLE timesheets DROP FOREIGN KEY FK_9AC77D2E9D86650F');
        $this->addSql('ALTER TABLE timesheets DROP FOREIGN KEY FK_9AC77D2E6C1197C9');
        $this->addSql('ALTER TABLE timesheets DROP FOREIGN KEY FK_9AC77D2EB8E08577');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE bills');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_subscription');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE employee_skills');
        $this->addSql('DROP TABLE modes_of_conversation');
        $this->addSql('DROP TABLE password_reset_request');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE subscription_company');
        $this->addSql('DROP TABLE subscription_duration');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE time_line');
        $this->addSql('DROP TABLE timeline_project');
        $this->addSql('DROP TABLE timesheets');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
