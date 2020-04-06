<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Enum\TaskStatus;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200405093004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tasks Table';
    }

    public function up(Schema $schema): void
    {
        $defaultStatus = TaskStatus::IN_PROGRESS();

        $this->addSql("CREATE TABLE task
        (
            id          INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            assignee    INTEGER UNSIGNED,
            description TEXT NOT NULL,
            status      VARCHAR(30) NOT NULL DEFAULT '${defaultStatus}',
            modified    BOOLEAN NOT NULL DEFAULT FALSE,
            
            FOREIGN KEY (assignee) REFERENCES user (id) ON DELETE SET NULL
        ) ENGINE = INNODB;");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE task');
    }
}
