<?php

require_once 'utils/Jwt.php';
require_once 'models/User.php';
require_once 'models/RefreshToken.php';

class RefreshController extends Controller {
    private Jwt $jwt;
    private User $userModel;
    private RefreshToken $refreshTokenModel;

    public function __construct() {
        parent::__construct();
        $this->jwt = new Jwt(SECRET_KEY);
        $this->userModel = new User();
        $this->refreshTokenModel = new RefreshToken(SECRET_KEY);
    }

    public function index(): void {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            header("Allow: POST");
            exit;
        }

        $this->refreshToken();
    }

    private function refreshToken(): void {
        if (!isset($_COOKIE['refreshToken'])) {
            $this->jsonResponse(['message' => 'invalid token'], 400);
        }
        $token = $_COOKIE['refreshToken'];

        try {
            $payload = $this->jwt->decode($token);
        } catch (Exception) {
            $this->jsonResponse(['message' => 'invalid token catch'], 400);
        }

        $user_id = $payload['id'];
        $refresh_token = $this->refreshTokenModel->getByToken($token);

        if (!$refresh_token) {
            $this->jsonResponse(['message' => 'invalid token'], 400);
        }

        $user = $this->userModel->getUserById($user_id);

        if (!$user) {
            $this->jsonResponse(['message' => 'invalid user'], 400);
        }

        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "exp" => time() + 900
        ];

        $access_token = $this->jwt->encode($payload);
        $refresh_token_expiry = time() + 432000;

        $refresh_token = $this->jwt->encode([
            "id" => $user["id"],
            "exp" => $refresh_token_expiry
        ]);

        $this->refreshTokenModel->create($refresh_token, $refresh_token_expiry);

        setcookie("refreshToken", $refresh_token, [
            "httponly" => true,
            "secure" => true,
            "samesite" => "Strict",
            "path" => "/refresh",
            "expires" => $refresh_token_expiry,
        ]);

        $this->jsonResponse([
            "access_token" => $access_token
        ]);
    }
}
