<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011204442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make the `description` oh the `Task` NULLABLE';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE task 
                CHANGE description description LONGTEXT DEFAULT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE task
                CHANGE description description LONGTEXT NOT NULL
        ');
    }
}
