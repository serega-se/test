<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210110215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_647bcb78aa334807');
        $this->addSql('DROP INDEX uniq_647bcb781e27f6bf');
        $this->addSql('ALTER TABLE user_statistic ALTER answer_id DROP NOT NULL');
        $this->addSql('CREATE INDEX IDX_647BCB781E27F6BF ON user_statistic (question_id)');
        $this->addSql('CREATE INDEX IDX_647BCB78AA334807 ON user_statistic (answer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_647BCB781E27F6BF');
        $this->addSql('DROP INDEX IDX_647BCB78AA334807');
        $this->addSql('ALTER TABLE user_statistic ALTER answer_id SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_647bcb78aa334807 ON user_statistic (answer_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_647bcb781e27f6bf ON user_statistic (question_id)');
    }
}
