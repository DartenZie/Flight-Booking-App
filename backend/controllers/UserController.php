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
        $data = $this->authenticateJWTToken();

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            header('ALLOW: GET');
            exit();
        }

        $user = $this->user->getUserById($data['id']);
        $this->jsonResponse($user);
    }
}
