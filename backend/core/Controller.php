<?php

require_once 'utils/Jwt.php';
require_once 'models/Role.php';
require_once 'models/User.php';

/**
 * This is a base controller class that provides common functionality for  handling API responses and JWT-based authentication.
 * It is intended to be extended by other controller classes.
 */
abstract class Controller {
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
        $this->jwt = new Jwt(SECRET_KEY);
        $this->role = new Role();
        $this->user = new User();
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
                $this->errorResponse('forbidden_action', 403);
            }
        } catch (InvalidArgumentException) {
            $this->errorResponse('invalid_login_credentials', 401);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
}
