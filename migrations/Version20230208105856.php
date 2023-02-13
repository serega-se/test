<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208105856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE statistic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE statistic (id INT NOT NULL, answer_id INT DEFAULT NULL, count_answers INT DEFAULT NULL, fraction INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_649B469CAA334807 ON statistic (answer_id)');
        $this->addSql('ALTER TABLE statistic ADD CONSTRAINT FK_649B469CAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer DROP is_correct');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE statistic_id_seq CASCADE');
        $this->addSql('ALTER TABLE statistic DROP CONSTRAINT FK_649B469CAA334807');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('ALTER TABLE answer ADD is_correct BOOLEAN NOT NULL');
    }
}
