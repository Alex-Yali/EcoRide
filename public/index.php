<?php

//Charger l'autoload
require_once __DIR__ . "/../vendor/autoload.php";

// On définit une constante pour avoir le chemin racine de l'app
define('APP_ROOT', dirname(__DIR__));
define('APP_ENV', ".env");

// Charger les services
require APP_ROOT . "/src/Service/Csrf.php";
require APP_ROOT . "/src/Service/Init.php";

use App\Db\Mysql;
use App\Routing\Router;

// Initialiser la base
$mysql = Mysql::getInstance();

// Lancer le router
$router = new Router();
$router->handleRequest($_SERVER['REQUEST_URI']);
