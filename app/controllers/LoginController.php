<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\RefreshToken;
use App\utils\InputValidator;
use App\utils\RequestUtils;

/**
 * Handles user login and token generation.
 */
class LoginController extends Controller {
    /**
     * @var RequestUtils Utility class for handling common request operations.
     */
    private RequestUtils $requestUtils;
    /**
     * @var RefreshToken Instance of the RefreshToken model for data operations.
     */
    private RefreshToken $refreshTokenModel;

    /**
     * Initializes the controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->requestUtils = new RequestUtils(SECRET_KEY);
        $this->refreshTokenModel = new RefreshToken($this->db, SECRET_KEY);
    }

    /**
     * Main endpoint for the login route.
     */
    public function index(): void {
        $this->handleRequest([
            'POST$' => fn() => $this->loginUser()
        ]);
    }

    /**
     * Validates user credentials and generates access and refresh tokens.
     *
     * @throws ValidationException If validation or sanitization.
     */
    private function loginUser(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['email', 'password']);

        $userEmail = InputValidator::sanitizeEmail($data['email']);
        $user = $this->user->getUserByEmail($userEmail);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new ValidationException('Invalid login credentials.', 401);
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
