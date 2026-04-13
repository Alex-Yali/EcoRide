<?php

namespace App\Routing;

use App\Controller\ErrorController;
use Exception;

class Router
{
    private $routes;
    public function __construct()
    {
        $this->routes = require_once APP_ROOT . "/config/routes.php";
    }

    public function handleRequest(string $uri)
    {
        try {
            $path = $this->normalizePath($uri);
            if (!isset($this->routes[$path])) {
                throw new Exception("La route n'existe pas");
            }
            $route = $this->routes[$path];

            $controllerPath = $route["controller"];
            $action = $route["action"];

            if (!class_exists($controllerPath)) {
                throw new Exception("La classe n'existe pas");
            }
            $controller = new $controllerPath();

            if (!method_exists($controller, $action)) {
                throw new Exception("L'action n'existe pas");
            }
            $controller->$action();
        } catch (Exception $e) {
            $errorController = new ErrorController();
            $errorController->show($e->getMessage());
        }
    }

    public static function normalizePath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, "/") . "/";
        return $path;
    }
}
