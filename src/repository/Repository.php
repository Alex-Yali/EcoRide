<?php

namespace App\repository;

use App\Db\Mysql;
use PDO;

class Repository
{
    protected PDO $pdo;

    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $this->pdo = $mysql->getPDO();
    }
}
