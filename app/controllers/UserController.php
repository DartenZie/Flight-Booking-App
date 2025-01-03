<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\utils\InputValidator;

class UserController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(): void {
        $this->authenticateJWTToken();

        $this->handleRequest([
            'GET' => fn() => $this->getCurrentUser(),
            'PUT' => fn() => $this->updateCurrentUser(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    private function getCurrentUser(): void {
        $user = $this->user->getUserById($this->userData['id']);
        if (!$user) {
            throw new ValidationException('User not found.', 404);
        }

        $userDetails = $this->mapUser($user);
        $this->jsonResponse($userDetails);
    }

    /**
     * @throws ValidationException
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

    private function mapUser(array $user): array {
        return [
            'id' => $user['id'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'email' => $user['email'],
            'nationality' => $user['nationality'],
            'dateOfBirth' => $user['date_of_birth'],
            'phone' => $user['phone'],
            'sex' => $user['sex'],
            'permissionLevel' => $user['permission_level'],
            'createdAt' => $user['created_at']
        ];
    }
}
