<?php

require_once 'models/User.php';
require_once 'core/Controller.php';

class UserController extends Controller {
    private User $user;

    public function __construct() {
        parent::__construct();
        $this->user = new User();
    }

    public function index(): void {
        $this->authenticateJWTToken();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $this->getUserById($this->userData); break;
            case 'PUT': $this->updateUser($this->userData['id']); break;
            default:
                http_response_code(405);
                header('ALLOW: GET, PUT');
                exit();
        }
    }

    private function getUserById(array $data): void {
        $user = $this->user->getUserById($data['id']);
        $this->jsonResponse($user);
    }

    private function updateUser(int $id): void {
        $user = $this->user->getUserById($id);

        $data = json_decode(file_get_contents('php://input'), true);

        $updateData = [
            'id' => $id,
            'firstName' => $data['firstName'] ?? $user['firstName'],
            'lastName' => $data['lastName'] ?? $user['lastName'],
            'email' => $data['email'] ?? $user['email'],
            'nationality' => $data['nationality'] ?? $user['nationality'] ?? null,
            'dateOfBirth' => $data['dateOfBirth'] ?? $user['dateOfBirth'] ?? null,
            'phone' => $data['phone'] ?? $user['phone'] ?? null,
            'sex' => $data['sex'] ?? $user['sex'] ?? null,
        ];

        $this->user->updateUser($updateData);
        $this->jsonResponse($updateData);
    }
}
