<?php

namespace App\Core;

use Throwable;

class Router {
    private string $route;

    public function __construct() {
        // Enable error reporting for debugging (remove in production)
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Cache-Control");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        set_exception_handler([$this, 'handleException']);

        $this->route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    /**
     * Determines the route based on the requested URI and delegates handling to the appropriate method.
     */
    public function route(): void {
        $uri = $_SERVER['REQUEST_URI'];

        if (str_starts_with($uri, '/api/')) {
            $this->handleApiRequest();
        } else {
            $this->serveFrontend();
        }
    }

    /**
     * Handles incoming API requests by resolving the route, instantiating the appropriate controller,
     * and invoking the specified method. Sends error responses if the controller or method cannot be found.
     */
    private function handleApiRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        [$controllerName, $methodName] = $this->resolveRoute();

        if (!class_exists($controllerName)) {
            $this->sendErrorResponse("Controller '$controllerName' not found");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            $this->sendErrorResponse("Method '{$methodName}' not found in controller '{$controllerName}'");
        }

        $controller->$methodName();
    }

    /**
     * Serves frontend assets or falls back to the main HTML file if the requested asset is not found.
     */
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

    /**
     * Resolves the current route into a controller class and method.
     *
     * @return array An array containing the controller class name as the first element
     *               and the method name as the second element.
     */
    private function resolveRoute(): array {
        if (empty($this->route)) {
            return ['App\Controllers\HomeController', 'index'];
        }

        $routeParts = explode('/', $this->route);
        $controllerName = 'App\Controllers\\' . ucfirst($routeParts[1]) . 'Controller';
        $methodName = $routeParts[2] ?? 'index';

        return [$controllerName, $methodName];
    }

    /**
     * Sends an error response with a 404 HTTP status code and a JSON-encoded error message.
     *
     * @param string $message The error message to include in the response.
     */
    private function sendErrorResponse(string $message): void {
        http_response_code(404);
        echo json_encode(['error' => $message]);
        exit;
    }

    /**
     * Determines the MIME type of a given file, with fallback logic for certain file types.
     *
     * @param string $file The file path for which the MIME type needs to be determined.
     * @return string The MIME type of the file, or a default MIME type if determination fails.
     */
    private function getMimeType(string $file): string {
        $mimeType = mime_content_type($file);

        if ($mimeType === false || $mimeType === 'text/plain' || $mimeType === 'text/x-java') {
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

    /**
     * Handles exceptions by setting an appropriate HTTP response code and returning a JSON-encoded error message.
     *
     * @param Throwable $exception The exception instance to be handled.
     */
    public function handleException(Throwable $exception): void
    {
        http_response_code(500);
        header('Content-Type: application/json');
        // TODO remove for production
        echo json_encode(['error' => $exception->getMessage()]);
//        echo json_encode(['error' => 'An unexpected error occurred.']);
    }
}
