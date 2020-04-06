<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Security\Password;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200405095513 extends AbstractMigration
{
    private $users = [
        [
            'name' => 'admin',
            'email' => 'admin',
            'password' => '123',
            'role' => 'ADMIN'
        ],
        [
            'name' => 'ivan',
            'email' => 'ivan@mail.ru',
            'password' => '456',
            'role' => 'USER'
        ],
        [
            'name' => 'vovan',
            'email' => 'vovan@mail.ru',
            'password' => '789',
            'role' => 'USER'
        ],
    ];

    public function getDescription(): string
    {
        return 'Generate Users';
    }

    public function up(Schema $schema): void
    {
        foreach ($this->users as $user) {
            $name = $user['name'];
            $email = $user['email'];
            $passwordHash = Password::encrypt($user['password']);
            $role = $user['role'];

            $this->addSql(sprintf("INSERT INTO user (name, email, role, pwd_hash) VALUES ('${name}', '${email}', '${role}', '${passwordHash}')"));
        }
    }

    public function down(Schema $schema): void
    {
        foreach ($this->users as $user) {
            $email = $user['email'];

            $this->addSql(sprintf("DELETE FROM user WHERE email = '${email}'"));
        }
    }
}
