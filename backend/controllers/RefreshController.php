<?php

require_once 'utils/RequestUtils.php';
require_once 'models/User.php';
require_once 'models/RefreshToken.php';
require_once 'core/Controller.php';

/**
 * Handles token refreshing.
 */
class RefreshController extends Controller {
    private RequestUtils $requestUtils;
    private User $userModel;
    private RefreshToken $refreshTokenModel;

    public function __construct() {
        parent::__construct();
        $this->requestUtils = new RequestUtils(SECRET_KEY);
        $this->userModel = new User();
        $this->refreshTokenModel = new RefreshToken(SECRET_KEY);
    }

    /**
     * Entry point for the refresh token route.
     * Responds only to POST requests.
     */
    public function index(): void {
        try {
            RequestUtils::validateJsonRequest('POST', 'application/json');
            $this->refreshToken();
        } catch (Exception $e) {
            $this->jsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Handles the logic for refreshing access tokens.
     */
    private function refreshToken(): void {
        if (!isset($_COOKIE['refreshToken'])) {
            $this->jsonResponse(['message' => 'Invalid token'], 400);
        }
        $token = $_COOKIE['refreshToken'];

        try {
            $payload = $this->requestUtils->validateToken($token);
        } catch (Exception $e) {
            $this->jsonResponse(['message' => $e->getMessage()], 400);
        }

        $user_id = $payload['sub'] ?? null;
        if (!$user_id || !$this->refreshTokenModel->getByToken($token)) {
            $this->jsonResponse(['message' => 'Invalid token'], 400);
        }

        $user = $this->userModel->getUserById($user_id);
        if (!$user) {
            $this->jsonResponse(['message' => 'Invalid user'], 400);
        }

        $access_token_expiry = time() + ACCESS_TOKEN_EXPIRY;
        $refresh_token_expiry = time() + REFRESH_TOKEN_EXPIRY;

        $access_token = $this->requestUtils->generateAccessToken($user, $access_token_expiry);
        $refresh_token = $this->requestUtils->generateRefreshToken($user, $refresh_token_expiry);

        // Save new refresh token into database.
        $this->refreshTokenModel->create($refresh_token, $refresh_token_expiry);

        // Set cookie for further refreshes.
        RequestUtils::setRefreshTokenCookie($refresh_token, $refresh_token_expiry);

        $this->jsonResponse(["access_token" => $access_token]);
    }
}
