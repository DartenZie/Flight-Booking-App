<?php

require_once 'models/User.php';
require_once 'models/RefreshToken.php';
require_once 'core/Controller.php';
require_once 'utils/Jwt.php';

class LoginController extends Controller {
    private User $userModel;
    private RefreshToken $refreshTokenModel;
    private Jwt $jwt;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->refreshTokenModel = new RefreshToken(SECRET_KEY);
        $this->jwt = new Jwt(SECRET_KEY);
    }

    public function index(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('ALLOW: POST');
            exit();
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType !== 'application/json') {
            $this->jsonResponse(['message' => 'Only JSON content is supported.'], 415);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            $this->jsonResponse(['message' => 'No data found.'], 400);
        }

        if (!array_key_exists('email', $data) || !array_key_exists('password', $data)) {
            $this->jsonResponse(['message' => 'Missing login credentials.'], 400);
        }

        $this->loginUser($data);
    }

    private function loginUser($data): void {
        $user = $this->userModel->getUserByEmail($data['email']);

        if ($user === false) {
            $this->jsonResponse(['message' => 'Invalid login credentials.'], 401);
        }

        if (!password_verify($data['password'], $user['password'])) {
            $this->jsonResponse(['message' => 'Invalid login credentials.'], 401);
        }

        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "exp" => time() + 900
        ];

        $refresh_token_expiry = time() + 432000;

        $access_token = $this->jwt->encode($payload);
        $refresh_token = $this->refreshToken($user, $refresh_token_expiry);

        $this->refreshTokenModel->create($refresh_token, $refresh_token_expiry);

        setcookie("refreshToken", $refresh_token, [
            "httponly" => true,
            "secure" => true,
            "samesite" => "Strict",
            "path" => "/refresh",
            "expires" => $refresh_token_expiry,
        ]);

        $this->jsonResponse([
            "access_token" => $access_token,
        ]);
    }

    private function refreshToken(array $user, int $expiry): string {
        return $this->jwt->encode([
            "id" => $user["id"],
            "exp" => $expiry
        ]);
    }
}
