<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603212712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE credit_log_action (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, dir VARCHAR(130) NOT NULL, name VARCHAR(50) NOT NULL, length INT NOT NULL, md5 VARCHAR(32) NOT NULL, created DATETIME NOT NULL, job_id VARCHAR(55) DEFAULT NULL, default_ctm LONGTEXT DEFAULT NULL, plain_text LONGTEXT DEFAULT NULL, words_count INT DEFAULT NULL, confidence DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_file (id INT AUTO_INCREMENT NOT NULL, file_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(80) NOT NULL, text JSON DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_F61E7AD993CB796C (file_id), INDEX IDX_F61E7AD9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, action_id INT NOT NULL, user_file_id INT DEFAULT NULL, amount INT NOT NULL, created DATETIME NOT NULL, INDEX IDX_20444CC1A76ED395 (user_id), INDEX IDX_20444CC19D32F035 (action_id), UNIQUE INDEX UNIQ_20444CC1CBC66766 (user_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_file ADD CONSTRAINT FK_F61E7AD993CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE user_file ADD CONSTRAINT FK_F61E7AD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credit_log ADD CONSTRAINT FK_20444CC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credit_log ADD CONSTRAINT FK_20444CC19D32F035 FOREIGN KEY (action_id) REFERENCES credit_log_action (id)');
        $this->addSql('ALTER TABLE credit_log ADD CONSTRAINT FK_20444CC1CBC66766 FOREIGN KEY (user_file_id) REFERENCES user_file (id)');
        $this->addSql('ALTER TABLE user ADD credits INT NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('INSERT INTO credit_log_action (name) VALUES
                            (\'scrybe_file\'),
                            (\'top_up_credits\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE credit_log DROP FOREIGN KEY FK_20444CC19D32F035');
        $this->addSql('ALTER TABLE user_file DROP FOREIGN KEY FK_F61E7AD993CB796C');
        $this->addSql('ALTER TABLE credit_log DROP FOREIGN KEY FK_20444CC1CBC66766');
        $this->addSql('DROP TABLE credit_log_action');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE user_file');
        $this->addSql('DROP TABLE credit_log');
        $this->addSql('ALTER TABLE user DROP credits, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('DELETE FROM credit_log_action WHERE name IN (\'scrybe_file\', \'top_up_credits\')');

    }
}
