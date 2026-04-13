<?php

namespace App\db;

use MongoDB\Client;

class MongoDB
{
    private static ?self $_instance = null;
    private ?Client $client = null;
    private $database;

    private function __construct()
    {
        $dbConf = parse_ini_file(APP_ROOT . "/" . APP_ENV);

        if ($dbConf === false) {
            throw new \Exception("Erreur lecture fichier .env");
        }

        $host = $dbConf['MONGO_HOST'];
        $port = $dbConf['MONGO_PORT'];
        $dbName = $dbConf['MONGO_NAME'];
        $user = $dbConf['MONGO_USER'];
        $pass = $dbConf['MONGO_PASS'];

        $uri = "mongodb://$user:$pass@$host:$port/?authSource=$dbName";

        $this->client = new Client($uri);
        $this->database = $this->client->selectDatabase($dbName);
    }

    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getCollection(string $name)
    {
        return $this->database->selectCollection($name);
    }
}
