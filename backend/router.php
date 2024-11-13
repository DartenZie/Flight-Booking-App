<?php

// TODO Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload controllers dynamically
spl_autoload_register(function ($className) {
    $controllerFile = "controllers/" . $className . ".php";
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
    } else {
        http_response_code(404);
        echo "Controller '$className' not found";
        exit;
    }
});

// Get the current route from the URL
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Determine the controller and method based on the route
if (empty($route)) {
    $controllerName = 'HomeController';
    $methodName = 'index';
} else {
    $routeParts = explode('/', $route);
    $controllerName = ucfirst($routeParts[0]) . "Controller";
    $methodName = isset($routeParts[1]) ? $routeParts[1] : "index";
}

// Check if the controller class exists
if (!class_exists($controllerName)) {
    http_response_code(404);
    echo "Controller '$controllerName' not found";
    exit;
}

// Create an instance of the controller
$controller = new $controllerName();

// Check if the method exists in the controller
if (!method_exists($controller, $methodName)) {
    http_response_code(404);
    echo "Method '{$methodName}' not found in controller '{$controllerName}'!";
    exit;
}

// Call the controller method
$controller->$methodName();
