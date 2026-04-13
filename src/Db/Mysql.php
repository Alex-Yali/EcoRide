<?php

namespace App\db;

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
        $dbConf = parse_ini_file(APP_ROOT . "/" . APP_ENV);

        if ($dbConf === false) {
            throw new \Exception("Erreur lecture fichier .env");
        }

        $this->dbHost = $dbConf["db_host"];
        $this->dbName = $dbConf["db_name"];
        $this->dbUser = $dbConf["db_user"];
        $this->dbPassword = $dbConf["db_password"];
        $this->dbPort = $dbConf["db_port"];
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
            $this->pdo = new PDO("mysql:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName};charset=utf8", $this->dbUser, $this->dbPassword);
        }
        return $this->pdo;
    }
}
