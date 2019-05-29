<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190527182001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file ADD file_txt LONGTEXT DEFAULT NULL, ADD file_confidence DOUBLE PRECISION DEFAULT NULL, ADD file_words INT DEFAULT NULL, CHANGE file_job_id file_job_id VARCHAR(55) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_file ADD userfile_text JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user_credit_log CHANGE ucl_userfile_id_id ucl_userfile_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file DROP file_txt, DROP file_confidence, DROP file_words, CHANGE file_job_id file_job_id VARCHAR(55) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE user_credit_log CHANGE ucl_userfile_id_id ucl_userfile_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_file DROP userfile_text');
    }
}
