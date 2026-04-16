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
        $host = $_ENV['MONGO_HOST'] ?? getenv('MONGO_HOST');
        $port = $_ENV['MONGO_PORT'] ?? getenv('MONGO_PORT');
        $dbName = $_ENV['MONGO_DB'] ?? getenv('MONGO_DB');
        $user = $_ENV['MONGO_USER'] ?? getenv('MONGO_USER');
        $pass = $_ENV['MONGO_PASS'] ?? getenv('MONGO_PASS');

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
