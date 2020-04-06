<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200405091019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Users Table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user
        (
            id       INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            name     VARCHAR(255) NOT NULL,
            email    VARCHAR(255) NOT NULL UNIQUE,
            role     VARCHAR(255) NOT NULL,
            pwd_hash VARCHAR(60) NOT NULL
        ) ENGINE = INNODB;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user;');
    }
}
