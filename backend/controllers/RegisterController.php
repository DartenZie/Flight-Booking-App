<?php

require_once 'models/User.php';
require_once 'core/Controller.php';

class RegisterController extends Controller {
    private User $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function index(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('ALLOW: POST');
            exit();
        }

        $this->registerUser();
    }

    private function registerUser(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $this->jsonResponse(['message' => 'All fields are required.'], 400);
            return;
        }

        if ($this->userModel->userExists($email)) {
            $this->jsonResponse(['message' => 'Email already exists.'], 400);
            return;
        }

        // TODO validations

        $this->userModel->createUser($firstName, $lastName, $email, $password);
        $this->jsonResponse(['error' => false]);
    }
}
