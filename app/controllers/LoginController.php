<?php

namespace App\controllers;

use App\core\Controller;
use App\models\RefreshToken;
use App\utils\RequestUtils;
use Exception;

/**
 * Handles user login and token generation.
 */
class LoginController extends Controller {
    private RequestUtils $requestUtils;
    private RefreshToken $refreshTokenModel;

    public function __construct() {
        parent::__construct();
        $this->requestUtils = new RequestUtils(SECRET_KEY);
        $this->refreshTokenModel = new RefreshToken($this->db, SECRET_KEY);
    }

    /**
     * Entry point for the login route.
     * Responds only to POST requests with JSON payload.
     */
    public function index(): void {
        try {
            RequestUtils::validateJsonRequest('POST', 'application/json');
            $data = RequestUtils::parseJsonInput();

            $this->loginUser($data);
        } catch (Exception $e) {
            $this->jsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Validates user credentials and generates access and refresh tokens.
     *
     * @param array $data Associative array containing 'email' and 'password' keys.
     */
    private function loginUser(array $data): void {
        $user = $this->user->getUserByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            $this->jsonResponse(['message' => 'Invalid login credentials.'], 401);
        }

        $access_token_expiry = time() + ACCESS_TOKEN_EXPIRY;
        $refresh_token_expiry = time() + REFRESH_TOKEN_EXPIRY;

        $access_token = $this->requestUtils->generateAccessToken($user, $access_token_expiry);
        $refresh_token = $this->requestUtils->generateRefreshToken($user, $refresh_token_expiry);

        // Save the refresh token to the database.
        $this->refreshTokenModel->create($refresh_token, $refresh_token_expiry);

        // Set cookie for refresh token.
        RequestUtils::setRefreshTokenCookie($refresh_token, $refresh_token_expiry);

        $this->jsonResponse(['access_token' => $access_token]);
    }
}
