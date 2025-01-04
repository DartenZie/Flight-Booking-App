<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\utils\InputValidator;
use App\utils\MapperUtils;

/**
 * Handles requests related to users.
 */
class UserController extends Controller {
    /**
     * Initializes the controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Handles different HTTP methods for the current user endpoint.
     */
    public function index(): void {
        $this->authenticateJWTToken();

        $this->handleRequest([
            'GET' => fn() => $this->getCurrentUser(),
            'PUT' => fn() => $this->updateCurrentUser(),
        ]);
    }

    /**
     * Handles the listing of all users.
     */
    public function list(): void {
        $this->authenticateJWTToken('admin');

        $this->handleRequest([
            'GET' => fn() => $this->getAllUsers(),
        ]);
    }

    /**
     * Handles the update operation for users based on the current request.
     */
    public function update(): void {
        $this->authenticateJWTToken('admin');

        $this->handleRequest([
            'PUT' => fn() => $this->updateUser(),
        ]);
    }

    /**
     * Retrieves the current user by ID from user data, maps the user details,
     * and sends the response in JSON format. Throws an exception if the user is not found.
     *
     * @throws ValidationException if the user is not found.
     */
    private function getCurrentUser(): void {
        $user = $this->user->getUserById($this->userData['id']);
        if (!$user) {
            throw new ValidationException('User not found.', 404);
        }

        $userDetails = MapperUtils::mapCurrentUser($user);
        $this->jsonResponse($userDetails);
    }

    /**
     * Retrieves a paginated list of all users.
     *
     * Fetches user data from the database based on the current page and limit,
     * maps user data to the desired format, and returns the response with
     * user information, total count, current page, and total pages.
     */
    private function getAllUsers(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $users = $this->user->getAllUsers($limit, $offset) ?? [];
        $totalUsers = $this->user->getUsersCount();

        $users = array_map(fn ($user) => MapperUtils::mapUser($user), $users);

        $this->jsonResponse([
            'users' => $users,
            'total' => $totalUsers,
            'page' => $page,
            'totalPages' => ceil($totalUsers / $limit)
        ]);
    }

    /**
     * Updates the current user with the provided data.
     *
     * The method parses the request body to retrieve the necessary data
     * and updates the current user using their ID.
     *
     * @throws ValidationException If validation/sanitization fails.
     */
    private function updateCurrentUser(): void {
        $data = $this->parseRequestBody();

        $user = $this->user->getUserById($this->userData['id']);
        if (!$user) {
            throw new ValidationException('User not found.', 404);
        }

        $updateData = [];
        $fieldMappings = [
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'email' => 'email',
            'nationality' => 'nationality',
            'dateOfBirth' => 'date_of_birth',
            'phone' => 'phone',
            'sex' => 'sex'
        ];

        foreach ($fieldMappings as $inputField => $dbField) {
            if (isset($data[$inputField])) {
                $sanitizer = match ($inputField) {
                    'firstName', 'lastName', 'nationality', 'phone' => 'sanitizeString',
                    'email' => 'sanitizeEmail',
                    'dateOfBirth' => 'sanitizeDate',
                    'sex' => 'sanitizeSex',
                    default => null
                };
                if ($sanitizer) {
                    $updateData[$dbField] = InputValidator::$sanitizer($data[$inputField]);
                }
            }
        }

        $this->user->updateUser($user['id'], $updateData);
        $this->jsonResponse(['message' => 'User updated successfully.']);
    }

    /**
     * Updates the user based on the provided request data.
     *
     * Parses the incoming request body to extract user data, validates required fields,
     * and updates the user identified by the specified ID.
     *
     * @throws ValidationException If validation/sanitization fails.
     */
    private function updateUser(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['id']);

        $id = InputValidator::sanitizeInt($data['id']);
        $user = $this->user->getUserById($id);
        if (!$user) {
            throw new ValidationException('User not found.', 404);
        }
        if ($user['role_id'] === 3) {
            throw new ValidationException('You can\'t update other admin.', 403);
        }

        $updateData = [];
        $fieldMappings = [
            'roleId' => 'role_id',
        ];

        foreach ($fieldMappings as $inputField => $dbField) {
            if (isset($data[$inputField])) {
                $sanitizer = match ($inputField) {
                    'roleId' => 'sanitizeInt',
                    default => null
                };
                if ($sanitizer) {
                    $updateData[$dbField] = InputValidator::$sanitizer($data[$inputField]);
                }
            }
        }

        $this->user->updateUser($user['id'], $updateData);
        $this->jsonResponse(['message' => 'User updated successfully.']);
    }
}
