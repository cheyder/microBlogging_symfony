<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112132822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posts_likes (micro_post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FBCD156C11E37CEA (micro_post_id), INDEX IDX_FBCD156CA76ED395 (user_id), PRIMARY KEY(micro_post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posts_likes ADD CONSTRAINT FK_FBCD156C11E37CEA FOREIGN KEY (micro_post_id) REFERENCES micro_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_likes ADD CONSTRAINT FK_FBCD156CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE posts_likes');
    }
}
