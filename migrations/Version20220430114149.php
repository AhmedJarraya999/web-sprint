<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430114149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profanities (id INT AUTO_INCREMENT NOT NULL, word VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B8715B4C3F17511 (word), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY comment_ibfk_2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY comment_ibfk_3');
        $this->addSql('DROP INDEX IDX_9474526CBDAFD8C8 ON comment');
        $this->addSql('DROP INDEX author ON comment');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C981ABA41 FOREIGN KEY (id_exp) REFERENCES experience (id)');
        $this->addSql('CREATE INDEX author ON comment (author)');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY experience_ibfk_1');
        $this->addSql('DROP INDEX idAuthor ON experience');
        $this->addSql('ALTER TABLE experience CHANGE date date VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE profanities');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C981ABA41');
        $this->addSql('DROP INDEX author ON comment');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT comment_ibfk_2 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT comment_ibfk_3 FOREIGN KEY (id_exp) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9474526CBDAFD8C8 ON comment (author)');
        $this->addSql('CREATE INDEX author ON comment (author, id_exp)');
        $this->addSql('ALTER TABLE experience CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT experience_ibfk_1 FOREIGN KEY (id_author) REFERENCES user (id)');
        $this->addSql('CREATE INDEX idAuthor ON experience (id_author)');
    }
}