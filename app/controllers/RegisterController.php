<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\utils\InputValidator;

/**
 * Handles uer register.
 */
class RegisterController extends Controller {
    /**
     * Initializes the controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Main endpoint for the register route.
     */
    public function index(): void {
        $this->handleRequest([
            'POST' => fn() => $this->registerUser()
        ]);
    }

    /**
     * Handles the user registration process by validating input data, checking for existing users,
     * and creating a new user account if validation passes.
     *
     * @throws ValidationException If the input data is invalid or a user with the provided email already exists.
     */
    private function registerUser(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['firstName', 'lastName', 'email', 'password']);

        $userEmail = InputValidator::sanitizeEmail($data['email']);
        $user = $this->user->getUserByEmail($userEmail);

        if ($user) {
            throw new ValidationException('User with this email already exists.', 400);
        }

        $createData = [
            'firstName' => InputValidator::sanitizeString($data["firstName"]),
            'lastName' => InputValidator::sanitizeString($data["lastName"]),
            'email' => $userEmail,
            'passwordHash' => password_hash($data["password"], PASSWORD_DEFAULT)
        ];

        $this->user->createUser($createData);
        $this->jsonResponse(['message' => 'User created successfully.']);
    }
}
