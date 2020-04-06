<?php


namespace App\Service;


use App\Enum\UserRole;

class UserService extends BaseService
{
    public function findUserByEmail(string $email): ?array
    {
        $query = "
            SELECT id, name, email, role, pwd_hash
            FROM user
            WHERE email = '${email}';
        ";

        $result = $this->query($query);
        if (1 === count($result)) {
            $user = $result[0];
            $user['is_admin'] = $user['role'] === UserRole::ADMIN()->getValue();
            return $user;
        }
        return null;
    }

    public function findAssignees(): array
    {
        $userRole = UserRole::USER();
        $query = "
            SELECT id, name
            FROM user
            WHERE role = '${userRole}'
        ";

        return $this->query($query);
    }
}