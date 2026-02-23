<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223205101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAE92F8F78 FOREIGN KEY (recipient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA10DAF24A FOREIGN KEY (actor_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user_follow ADD CONSTRAINT FK_D665F4DAC24F853 FOREIGN KEY (follower_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_follow ADD CONSTRAINT FK_D665F4D1816E3A3 FOREIGN KEY (following_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_game_collection ADD plateforme VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAE92F8F78');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA10DAF24A');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAE48FD905');
        $this->addSql('ALTER TABLE user_follow DROP FOREIGN KEY FK_D665F4DAC24F853');
        $this->addSql('ALTER TABLE user_follow DROP FOREIGN KEY FK_D665F4D1816E3A3');
        $this->addSql('ALTER TABLE user_game_collection DROP plateforme');
    }
}
