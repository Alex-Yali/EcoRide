<?php

namespace App\Db;

use PDO;

class Mysql
{

    private string $dbHost;
    private string $dbName;
    private string $dbUser;
    private string $dbPassword;
    private string $dbPort;

    private ?PDO $pdo = null;
    private static ?self $_instance = null;

    private function __construct()
    {
        $this->dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
        $this->dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
        $this->dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER');
        $this->dbPassword = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
        $this->dbPort = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
    }

    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Mysql();
        }
        return self::$_instance;
    }

    public function getPDO(): PDO
    {
        if (is_null($this->pdo)) {
            $this->pdo = new PDO(
                "mysql:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName};charset=utf8mb4",
                $this->dbUser,
                $this->dbPassword,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return $this->pdo;
    }
}
