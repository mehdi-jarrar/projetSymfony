<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830041022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_news DROP FOREIGN KEY FK_9648E317B5A459A0');
        $this->addSql('ALTER TABLE news_user DROP FOREIGN KEY FK_584E20C2B5A459A0');
        $this->addSql('CREATE TABLE category_dishes (category_id INT NOT NULL, dishes_id INT NOT NULL, INDEX IDX_57A5192612469DE2 (category_id), INDEX IDX_57A51926A05DD37A (dishes_id), PRIMARY KEY(category_id, dishes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dishes (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, subject VARCHAR(2048) NOT NULL, image VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dishes_user (dishes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2EF47290A05DD37A (dishes_id), INDEX IDX_2EF47290A76ED395 (user_id), PRIMARY KEY(dishes_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_dishes ADD CONSTRAINT FK_57A5192612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_dishes ADD CONSTRAINT FK_57A51926A05DD37A FOREIGN KEY (dishes_id) REFERENCES dishes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dishes_user ADD CONSTRAINT FK_2EF47290A05DD37A FOREIGN KEY (dishes_id) REFERENCES dishes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dishes_user ADD CONSTRAINT FK_2EF47290A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE category_news');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_user');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_dishes DROP FOREIGN KEY FK_57A51926A05DD37A');
        $this->addSql('ALTER TABLE dishes_user DROP FOREIGN KEY FK_2EF47290A05DD37A');
        $this->addSql('CREATE TABLE category_news (category_id INT NOT NULL, news_id INT NOT NULL, INDEX IDX_9648E31712469DE2 (category_id), INDEX IDX_9648E317B5A459A0 (news_id), PRIMARY KEY(category_id, news_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, subject VARCHAR(2048) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE news_user (news_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_584E20C2B5A459A0 (news_id), INDEX IDX_584E20C2A76ED395 (user_id), PRIMARY KEY(news_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_news ADD CONSTRAINT FK_9648E31712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_news ADD CONSTRAINT FK_9648E317B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news_user ADD CONSTRAINT FK_584E20C2A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news_user ADD CONSTRAINT FK_584E20C2B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE category_dishes');
        $this->addSql('DROP TABLE dishes');
        $this->addSql('DROP TABLE dishes_user');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
    }
}
