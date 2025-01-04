<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\RefreshToken;
use App\utils\InputValidator;
use App\utils\RequestUtils;
use Exception;

/**
 * Handles token refreshing.
 */
class RefreshController extends Controller {
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
     * Main endpoint for the refresh route.
     */
    public function index(): void {
        $this->handleRequest([
            'POST' => fn() => $this->refreshToken()
        ]);
    }

    /**
     * Handles the logic for refreshing access tokens.
     *
     * @throws ValidationException If validation fails.
     */
    private function refreshToken(): void {
        InputValidator::required($_COOKIE, ['refreshToken']);

        $token = $_COOKIE['refreshToken'];
        try {
            $payload = $this->requestUtils->validateToken($token);
        } catch (Exception $e) {
            throw new ValidationException('Invalid token.', 400);
        }

        InputValidator::required($payload, ['sub']);

        $user_id = $payload['sub'];
        if (!$user_id || !$this->refreshTokenModel->getByToken($token)) {
            throw new ValidationException('Invalid token.', 400);
        }

        $user = $this->user->getUserById($user_id);
        if (!$user) {
            throw new ValidationException('Invalid token.', 400);
        }

        $access_token_expiry = time() + ACCESS_TOKEN_EXPIRY;
        $refresh_token_expiry = time() + REFRESH_TOKEN_EXPIRY;

        $access_token = $this->requestUtils->generateAccessToken($user, $access_token_expiry);
        $refresh_token = $this->requestUtils->generateRefreshToken($user, $refresh_token_expiry);

        // Save new refresh token into database.
        $this->refreshTokenModel->create($refresh_token, $refresh_token_expiry);
        $this->refreshTokenModel->deleteExpired();

        // Set cookie for further refreshes.
        RequestUtils::setRefreshTokenCookie($refresh_token, $refresh_token_expiry);

        $this->jsonResponse(["access_token" => $access_token]);
    }
}
