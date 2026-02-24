<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224091845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_game_collection CHANGE statut statut VARCHAR(20) NOT NULL');
        $this->addSql('CREATE INDEX idx_user_statut ON user_game_collection (user_id, statut)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_game_approved ON review');
        $this->addSql('DROP INDEX idx_user_statut ON user_game_collection');
        $this->addSql('ALTER TABLE user_game_collection CHANGE statut statut VARCHAR(255) NOT NULL');
    }
}
