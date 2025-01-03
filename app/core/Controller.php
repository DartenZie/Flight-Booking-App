<?php

namespace App\core;

use App\Exceptions\ValidationException;
use App\models\Role;
use App\models\User;
use App\utils\Jwt;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;

/**
 * This is a base controller class that provides common functionality for  handling API responses and JWT-based authentication.
 * It is intended to be extended by other controller classes.
 */
abstract class Controller {
    /**
     * @var PDO|null Database Connection
     */
    public ?PDO $db = null;
    /**
     * @var Jwt Instance of the JWT class used for encoding and decoding JWT tokens.
     */
    private Jwt $jwt;
    /**
     * @var Role Instance of the Role model used to fetch role info from db.
     */
    private Role $role;
    /**
     * @var User Instance of the User model used to fetch user info from db.
     */
    protected User $user;
    /**
     * @var array Instance of the array that includes information about the current user.
     */
    protected array $userData;

    protected function __construct() {
        $this->db = $this->openDatabaseConnection();

        $this->jwt = new Jwt(SECRET_KEY);
        $this->role = new Role($this->db);
        $this->user = new User($this->db);
    }

    /**
     * @throws ValidationException
     */
    protected function parseRequestBody(): array {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            throw new ValidationException('Invalid JSON payload.', 400);
        }
        return $data;
    }

    protected function handleRequest(array $handlers): void {
        $method = $_SERVER['REQUEST_METHOD'];
        if (array_key_exists($method, $handlers)) {
            try {
                $handlers[$method]();
            } catch (ValidationException $e) {
                $this->jsonResponse(['error' => $e->getMessage()], $e->getCode());
            }
        } else {
            $this->sendMethodNotAllowedResponse(array_keys($handlers));
        }
    }

    /**
     * Sends a JSON response with a specified HTTP status code and exits the script.
     *
     * @param mixed $data The data to be encoded as JSON and sent in the response.
     * @param int $statusCode The HTTP status code for the response (default is 200).
     */
    protected function jsonResponse(mixed $data, int $statusCode = 200): void {
        header('Content-type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function errorResponse(string $error, int $statusCode = 400): void {
        http_response_code($statusCode);
        echo $error;
        exit;
    }

    /**
     * Authenticates the request using a JWT token and checks that the user satisfies required role.
     *
     * @param string $requiredRole Required role to authorize the request.
     */
    protected function authenticateJWTToken(string $requiredRole = 'user'): void {
        if (!isset($_SERVER['HTTP_AUTHORIZATION']) || !preg_match("/^Bearer\s+(.*)$/", $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            $this->errorResponse('incomplete_login_credentials', 400);
        }

        try {
            $data = $this->jwt->decode($matches[1]);

            $this->userData = $this->user->getUserById($data['sub']);

            $perm_level = $this->role->getRoleByName($requiredRole)['permission_level'];
            if ($this->userData['permission_level'] < $perm_level) {
                $this->errorResponse($perm_level . ' ' . $this->userData['permission_level'] . 'forbidden_action', 403);
            }
        } catch (InvalidArgumentException) {
            $this->errorResponse('invalid_login_credentials', 401);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

    private function sendMethodNotAllowedResponse(array $allowedMethods): void {
        header('Allow: ' . implode(', ', $allowedMethods));
        $this->jsonResponse(['error' => 'Method not allowed.'], 405);
    }

    /**
     * Establishes a connection to the database using PDO.
     *
     * If the connection fails, it terminates the script with an error message.
     *
     * @return PDO|void The PDO connection instance.
     */
    private function openDatabaseConnection(): ?PDO {
        try {
            return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // Output an error message and terminate the script if the connection fails.
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
