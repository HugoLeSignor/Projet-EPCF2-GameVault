<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216094225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD igdb_id INT DEFAULT NULL, ADD image_url VARCHAR(500) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CF22E1B8D ON game (igdb_id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user_game_collection ADD CONSTRAINT FK_4DCE50A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_game_collection ADD CONSTRAINT FK_4DCE50E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_232B318CF22E1B8D ON game');
        $this->addSql('ALTER TABLE game DROP igdb_id, DROP image_url');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E48FD905');
        $this->addSql('ALTER TABLE user_game_collection DROP FOREIGN KEY FK_4DCE50A76ED395');
        $this->addSql('ALTER TABLE user_game_collection DROP FOREIGN KEY FK_4DCE50E48FD905');
    }
}
