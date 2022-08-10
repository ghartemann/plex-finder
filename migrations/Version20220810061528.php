<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810061528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE finder (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_C9EB34E68F93B6FC (movie_id), INDEX IDX_C9EB34E6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE finder ADD CONSTRAINT FK_C9EB34E68F93B6FC FOREIGN KEY (movie_id) REFERENCES watchlist (id)');
        $this->addSql('ALTER TABLE finder ADD CONSTRAINT FK_C9EB34E6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE finder DROP FOREIGN KEY FK_C9EB34E68F93B6FC');
        $this->addSql('ALTER TABLE finder DROP FOREIGN KEY FK_C9EB34E6A76ED395');
        $this->addSql('DROP TABLE finder');
    }
}
