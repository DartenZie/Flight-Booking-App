<?php

namespace App\controllers;

use App\core\Controller;
use App\models\User;

class UsersController extends Controller {
    private User $userModel;

    public function __construct()
    {
        parent::__construct();

        $this->userModel = new User($this->db);
    }

    public function index(): void {
        $this->authenticateJWTToken('admin');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $this->getUsers(); break;
            case 'PUT': $this->updateUserRole(); break;
            default:
                http_response_code(405);
                header('ALLOW: GET, PUT');
                exit();
        }
    }

    private function getUsers(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->getAllUsers($limit, $offset) ?? [];
        $totalUsers = $this->userModel->getUsersCount();

        $users = array_map(fn($user) => [
            'id' => $user['id'],
            'firstName' => $user['firstName'],
            'lastName' => $user['lastName'],
            'email' => $user['email'],
            'role' => $user['role_name']
        ], $users);

        $this->jsonResponse([
            'users' => $users,
            'total' => $totalUsers,
            'page' => $page,
            'totalPages' => ceil($totalUsers / $limit)
        ]);
    }

    private function updateUserRole(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            $this->jsonResponse(['message' => 'The user id is not defined.']);
        }
        if (!isset($data['role_id'])) {
            $this->jsonResponse(['message' => 'Role id is not defined']);
        }
        if ($this->userData['id'] === $data['id']) {
            $this->jsonResponse(['message' => 'You cannot change your own role.']);
        }

        $id = (int)$data;
        $user = $this->user->getUserById($id);
        $updateData = [
            'id' => $id,
            'firstName' => $user['firstName'],
            'lastName' => $user['lastName'],
            'email' => $user['email'],
            'nationality' => $user['nationality'] ?? null,
            'dateOfBirth' => $user['dateOfBirth'] ?? null,
            'phone' => $user['phone'] ?? null,
            'sex' => $user['sex'] ?? null,
            'role_id' => (int)$data['role_id']
        ];

        $this->userModel->updateUser($updateData);
        $this->jsonResponse($updateData);
    }
}
