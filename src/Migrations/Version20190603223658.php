<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603223658 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user_file CHANGE text text JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE credit_log DROP FOREIGN KEY FK_20444CC1CBC66766');
        $this->addSql('DROP INDEX UNIQ_20444CC1CBC66766 ON credit_log');
        $this->addSql('ALTER TABLE credit_log ADD file_id INT DEFAULT NULL, DROP user_file_id');
        $this->addSql('ALTER TABLE credit_log ADD CONSTRAINT FK_20444CC193CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_20444CC193CB796C ON credit_log (file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE credit_log DROP FOREIGN KEY FK_20444CC193CB796C');
        $this->addSql('DROP INDEX IDX_20444CC193CB796C ON credit_log');
        $this->addSql('ALTER TABLE credit_log ADD user_file_id INT DEFAULT NULL, DROP file_id');
        $this->addSql('ALTER TABLE credit_log ADD CONSTRAINT FK_20444CC1CBC66766 FOREIGN KEY (user_file_id) REFERENCES user_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20444CC1CBC66766 ON credit_log (user_file_id)');
        $this->addSql('ALTER TABLE file CHANGE job_id job_id VARCHAR(55) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE words_count words_count INT DEFAULT NULL, CHANGE confidence confidence DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE user_file CHANGE text text LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
    }
}
