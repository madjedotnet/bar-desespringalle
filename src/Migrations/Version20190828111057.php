<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190828111057 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_family (user_id INT NOT NULL, family_id INT NOT NULL, INDEX IDX_C0B43A66A76ED395 (user_id), INDEX IDX_C0B43A66C35E566A (family_id), PRIMARY KEY(user_id, family_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_family ADD CONSTRAINT FK_C0B43A66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_family ADD CONSTRAINT FK_C0B43A66C35E566A FOREIGN KEY (family_id) REFERENCES family (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_role ADD PRIMARY KEY (user_id, role_id)');
        $this->addSql('ALTER TABLE user_role RENAME INDEX idx_332ca4dda76ed395 TO IDX_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role RENAME INDEX idx_332ca4ddd60322ac TO IDX_2DE8C6A3D60322AC');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_family');
        $this->addSql('ALTER TABLE user_role DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_role ADD PRIMARY KEY (role_id, user_id)');
        $this->addSql('ALTER TABLE user_role RENAME INDEX idx_2de8c6a3d60322ac TO IDX_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE user_role RENAME INDEX idx_2de8c6a3a76ed395 TO IDX_332CA4DDA76ED395');
    }
}
