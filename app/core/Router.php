<?php

namespace App\Core;

class Router {
    private string $route;

    public function __construct() {
        // Enable error reporting for debugging (remove in production)
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Set headers
        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Cache-Control");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // Set error handler
        set_exception_handler([$this, 'handleException']);

        // Parse the route from the URL
        $this->route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public function route(): void {
        $uri = $_SERVER['REQUEST_URI'];

        if (str_starts_with($uri, '/api/')) {
            $this->handleApiRequest();
        } else {
            $this->serveFrontend();
        }
    }

    private function handleApiRequest(): void {
        // Serve OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Determine the controller and method
        [$controllerName, $methodName] = $this->resolveRoute();

        // Check if the controller class exists
        if (!class_exists($controllerName)) {
            $this->sendErrorResponse("Controller '$controllerName' not found");
        }

        // Create an instance of the controller
        $controller = new $controllerName();

        // Check if the method exists in the controller
        if (!method_exists($controller, $methodName)) {
            $this->sendErrorResponse("Method '{$methodName}' not found in controller '{$controllerName}'");
        }

        // Call the controller method
        $controller->$methodName();
    }

    private function serveFrontend(): void {
        $file = PUBLIC_FOLDER . $_SERVER['REQUEST_URI'];

        if (is_file($file)) {
            $mimeType = $this->getMimeType($file);
            header("Content-Type: $mimeType");
            readfile($file);
        } else {
            header("Content-Type: text/html");
            readfile(PUBLIC_FOLDER . '/index.html');
        }
    }

    private function resolveRoute(): array
    {
        if (empty($this->route)) {
            return ['App\Controllers\HomeController', 'index'];
        }

        $routeParts = explode('/', $this->route);
        $controllerName = 'App\Controllers\\' . ucfirst($routeParts[1]) . 'Controller';
        $methodName = $routeParts[2] ?? 'index';

        return [$controllerName, $methodName];
    }

    private function sendErrorResponse(string $message): void
    {
        http_response_code(404);
        echo json_encode(['error' => $message]);
        exit;
    }

    private function getMimeType(string $file): string {
        $mimeType = mime_content_type($file);

        if ($mimeType === false || $mimeType === 'text/plain' || $mimeType === 'text/x-java') {
            // Fallback based on file extension
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $mimeTypes = [
                'js'  => 'application/javascript',
                'css' => 'text/css',
                'html' => 'text/html',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
            ];

            return $mimeTypes[$extension] ?? 'application/octet-stream';
        }

        return $mimeType;
    }

    public function handleException(\Throwable $exception): void
    {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $exception->getMessage()]);
//        echo json_encode(['error' => 'An unexpected error occurred.']);
    }
}
