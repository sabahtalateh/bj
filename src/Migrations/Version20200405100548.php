<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Enum\TaskStatus;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\Version;
use Dotenv\Dotenv;
use PDO;

final class Version20200405100548 extends AbstractMigration
{

    private PDO $pdo;

    public function __construct(Version $version)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        dump(123);
        die();

        parent::__construct($version);
        $this->pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=bj;port=33306',
            'bj',
            '4195a0860b7a4782bfa9b842cae77d4d',
        );
    }

    public function getDescription(): string
    {
        return 'Generate Tasks';
    }

    public function up(Schema $schema): void
    {
        $stmt = $this->pdo->query("SELECT id FROM user WHERE email IN ('ivan@mail.ru', 'vovan@mail.ru')");
        $userIds = [];
        while ($row = $stmt->fetch()) {
            $userIds[] = $row['id'];
        }
        if (count($userIds) != 2) {
            throw new \Exception("No users with emails ('ivan@mail.ru', 'vovan@mail.ru')");
        }

        $tasks = [
            [
                'assignee' => $userIds[0],
                'description' => 'Поехать покататься',
                'status' => TaskStatus::IN_PROGRESS()
            ],
            [
                'assignee' => $userIds[0],
                'description' => 'Попить чайку',
                'status' => TaskStatus::IN_PROGRESS()
            ],
            [
                'assignee' => $userIds[1],
                'description' => 'Продуть форсунки',
                'status' => TaskStatus::IN_PROGRESS()
            ],
            [
                'assignee' => $userIds[1],
                'description' => 'Установить Шиndошс',
                'status' => TaskStatus::DONE()
            ],
            [
                'assignee' => $userIds[1],
                'description' => 'Переустановить Шиndошс',
                'status' => TaskStatus::IN_PROGRESS()
            ]
        ];

        foreach ($tasks as $task) {
            $assignee = $task['assignee'];
            $description = $task['description'];
            $status = $task['status'];

            $this->addSql("INSERT INTO task (assignee, description, status) VALUES ('${assignee}', '${description}', '${status}')");
        }
    }

    public function down(Schema $schema): void
    {
        $stmt = $this->pdo->query("SELECT id FROM user WHERE email IN ('ivan@mail.ru', 'vovan@mail.ru')");
        $userIds = [];
        while ($row = $stmt->fetch()) {
            $userIds[] = $row['id'];
        }
        $userIdsSQLPart = implode(',', $userIds);
        $this->addSql("DELETE FROM task WHERE assignee IN (${userIdsSQLPart})");
    }
}
