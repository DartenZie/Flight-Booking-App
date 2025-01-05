<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\RefreshToken;
use App\utils\InputValidator;
use App\utils\RequestUtils;
use Exception;

/**
 * Handles user logout and token removal.
 */
class LogoutController extends Controller {
    /**
     * @var RequestUtils Utility class for handling common request operations.
     */
    private RequestUtils $requestUtils;
    /**
     * @var RefreshToken Instance of the RefreshToken model for data operations
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
     * Main endpoint for the logout route.
     */
    public function index(): void {
        $this->handleRequest([
            'POST' => fn() => $this->logoutUser()
        ]);
    }

    /**
     * @throws ValidationException
     */
    private function logoutUser(): void {
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

        $this->refreshTokenModel->delete($token);

        RequestUtils::removeRefreshTokenCookie();
        $this->jsonResponse(["message" => "Successfully logged out."]);
    }
}
