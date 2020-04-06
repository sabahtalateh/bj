<?php

namespace App\Service;

use PDO;

abstract class BaseService
{
    private PDO $pdo;

    public function __construct()
    {
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPassword = $_ENV['DB_PASSWORD'];
        $dbHost = $_ENV['DB_HOST'];
        $dbPort = $_ENV['DB_PORT'];

        $this->pdo = new PDO(
            "mysql:host=${dbHost};dbname=${dbName};port=${dbPort}",
            $dbUser,
            $dbPassword,
        );
    }

    protected function query(string $query, int $fetchMode = PDO::FETCH_ASSOC)
    {
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll($fetchMode);
    }
}
