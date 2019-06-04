<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603214403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file CHANGE job_id job_id VARCHAR(55) DEFAULT NULL, CHANGE words_count words_count INT DEFAULT NULL, CHANGE confidence confidence DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user_file ADD scrybe_status INT NOT NULL, CHANGE text text JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE credit_log CHANGE user_file_id user_file_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE credit_log CHANGE user_file_id user_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file CHANGE job_id job_id VARCHAR(55) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE words_count words_count INT DEFAULT NULL, CHANGE confidence confidence DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE user_file DROP scrybe_status, CHANGE text text LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
    }
}
