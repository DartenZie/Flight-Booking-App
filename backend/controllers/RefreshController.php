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
        $data = (array) json_decode(file_get_contents("php://input"), true);

        if (!array_key_exists("token", $data)) {
            $this->jsonResponse(['message' => 'missing token'], 400);
        }

        try {
            $payload = $this->jwt->decode($data["token"]);
        } catch (Exception) {
            $this->jsonResponse(['message' => 'invalid token catch'], 400);
        }

        $user_id = $payload['id'];
        $refresh_token = $this->refreshTokenModel->getByToken($data['token']);

        if (!$refresh_token) {
            $this->jsonResponse(['message' => 'invalid token norefresh'], 400);
        }

        $user = $this->userModel->getUserById($user_id);

        if (!$user) {
            $this->jsonResponse(['message' => 'invalid user nouser'], 400);
        }

        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "exp" => time() + 20
        ];

        $access_token = $this->jwt->encode($payload);
        $refresh_token_expiry = time() + 432000;

        $refresh_token = $this->jwt->encode([
            "id" => $user["id"],
            "exp" => $refresh_token_expiry
        ]);

        $this->refreshTokenModel->create($refresh_token, $refresh_token_expiry);

        $this->jsonResponse([
            "access_token" => $access_token,
            "refresh_token" => $refresh_token
        ]);
    }
}
