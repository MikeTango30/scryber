<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516080400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE credit_log_actions (id INT AUTO_INCREMENT NOT NULL, cla_name VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, file_dir VARCHAR(130) NOT NULL, file_name VARCHAR(50) NOT NULL, file_length INT NOT NULL, file_md5 VARCHAR(32) NOT NULL, file_created DATETIME NOT NULL, file_job_id VARCHAR(55) DEFAULT NULL, file_default_ctm LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_file (id INT AUTO_INCREMENT NOT NULL, userfile_file_id_id INT NOT NULL, userfile_user_id_id INT NOT NULL, userfile_ctm LONGTEXT DEFAULT NULL, userfile_is_scrybed SMALLINT NOT NULL, userfile_created DATETIME NOT NULL, userfile_updated DATETIME NOT NULL, INDEX IDX_F61E7AD9DBD37700 (userfile_file_id_id), INDEX IDX_F61E7AD993923C6F (userfile_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_credit_log (id INT AUTO_INCREMENT NOT NULL, ucl_user_id_id INT NOT NULL, ucl_action_id_id INT NOT NULL, ucl_userfile_id_id INT DEFAULT NULL, ucl_credits INT NOT NULL, ucl_created DATETIME NOT NULL, INDEX IDX_87238A2DB45C176D (ucl_user_id_id), INDEX IDX_87238A2D714A6204 (ucl_action_id_id), INDEX IDX_87238A2D678434D6 (ucl_userfile_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_file ADD CONSTRAINT FK_F61E7AD9DBD37700 FOREIGN KEY (userfile_file_id_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE user_file ADD CONSTRAINT FK_F61E7AD993923C6F FOREIGN KEY (userfile_user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_credit_log ADD CONSTRAINT FK_87238A2DB45C176D FOREIGN KEY (ucl_user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_credit_log ADD CONSTRAINT FK_87238A2D714A6204 FOREIGN KEY (ucl_action_id_id) REFERENCES credit_log_actions (id)');
        $this->addSql('ALTER TABLE user_credit_log ADD CONSTRAINT FK_87238A2D678434D6 FOREIGN KEY (ucl_userfile_id_id) REFERENCES user_file (id)');
        $this->addSql('ALTER TABLE user ADD credits INT NOT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_credit_log DROP FOREIGN KEY FK_87238A2D714A6204');
        $this->addSql('ALTER TABLE user_file DROP FOREIGN KEY FK_F61E7AD9DBD37700');
        $this->addSql('ALTER TABLE user_credit_log DROP FOREIGN KEY FK_87238A2D678434D6');
        $this->addSql('DROP TABLE credit_log_actions');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE user_file');
        $this->addSql('DROP TABLE user_credit_log');
        $this->addSql('ALTER TABLE user DROP credits, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
