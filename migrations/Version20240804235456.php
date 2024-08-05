<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804235456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515314584665A');
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515311AD5CDBF');
        $this->addSql('ALTER TABLE cart_products ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515314584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515311AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart CHANGE id id CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515311AD5CDBF');
        $this->addSql('ALTER TABLE cart_products DROP FOREIGN KEY FK_2D2515314584665A');
        $this->addSql('ALTER TABLE cart_products DROP quantity, CHANGE cart_id cart_id CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', CHANGE product_id product_id CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515311AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_products ADD CONSTRAINT FK_2D2515314584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product CHANGE id id CHAR(36) NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
