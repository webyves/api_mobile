<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226173457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F689D86650F');
        $this->addSql('DROP INDEX IDX_A2161F689D86650F ON user_client');
        $this->addSql('ALTER TABLE user_client ADD zip_code VARCHAR(50) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD phone VARCHAR(50) DEFAULT NULL, ADD birth_date DATE DEFAULT NULL, ADD created_date DATETIME NOT NULL, CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F68A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A2161F68A76ED395 ON user_client (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_client DROP FOREIGN KEY FK_A2161F68A76ED395');
        $this->addSql('DROP INDEX IDX_A2161F68A76ED395 ON user_client');
        $this->addSql('ALTER TABLE user_client DROP zip_code, DROP city, DROP phone, DROP birth_date, DROP created_date, CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_client ADD CONSTRAINT FK_A2161F689D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A2161F689D86650F ON user_client (user_id_id)');
    }
}
