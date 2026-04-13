<?php

namespace App\Repository;

use App\db\Mysql;
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
