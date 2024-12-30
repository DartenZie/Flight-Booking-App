<?php

declare(strict_types=1);

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);

// Custom autoloader to load classes based on namespace
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class file for {$class} not found at {$file}");
    }
});

require APP . 'config/config.php';

use App\Core\Router;

// Define the public folder for serving static files
define('PUBLIC_FOLDER', __DIR__ . '/dist');

// Route incoming requests
$router = new Router();

// Serve either the API or the frontend
$router->route();
