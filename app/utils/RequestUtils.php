<?php

namespace App\utils;

use Exception;

/**
 * Utility class for handling common request operations.
 */
class RequestUtils {
    private Jwt $jwt;

    public function __construct(string $secretKey) {
        $this->jwt = new Jwt($secretKey);
    }

    /**
     * Validates the request method and content type.
     *
     * @param string $expectedMethod The expected HTTP method (e.g., 'POST').
     * @param string $expectedContentType The expected content type (e.g., 'application/json').
     * @throws Exception If validation fails.
     */
    public static function validateJsonRequest(string $expectedMethod, string $expectedContentType): void {
        if ($_SERVER['REQUEST_METHOD'] !== $expectedMethod) {
            http_response_code(405);
            header("Allow: $expectedMethod");
            throw new Exception('Invalid request method.');
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if ($contentType !== $expectedContentType) {
            http_response_code(415);
            throw new Exception('Unsupported content type.');
        }
    }

    /**
     * Sets the refresh token as an HTTP-only cookie.
     *
     * @param string $refreshToken The refresh token value.
     * @param int $refreshTokenExpiry Expiry timestamp for the refresh token.
     */
    public static function setRefreshTokenCookie(string $refreshToken, int $refreshTokenExpiry): void {
        setcookie("refreshToken", $refreshToken, [
            "httponly" => true,
            "secure" => true,
            "samesite" => "Strict",
            "path" => "/api/refresh",
            "expires" => $refreshTokenExpiry,
        ]);
    }

    /**
     * Decodes and validates a JWT token.
     *
     * @param string $token The JWT token to validate.
     * @return array The decoded payload.
     * @throws Exception If the token is invalid.
     */
    public function validateToken(string $token): array {
        try {
            return $this->jwt->decode($token);
        } catch (Exception) {
            throw new Exception("Invalid token");
        }
    }

    /**
     * Generate an access token.
     *
     * @param array $user User data to include in the token payload.
     * @param int $expiry Expiry timestamp for the token.
     * @return string The generated access token.
     */
    public function generateAccessToken(array $user, int $expiry): string {
        return $this->jwt->encode([
            'sub' => $user['id'],
            'perm_level' => $user['permission_level'],
            'expiry' => $expiry
        ]);
    }

    /**
     * Generate a refresh token.
     *
     * @param array $user User data to include in the token payload.
     * @param int $expiry Expiry timestamp for the token.
     * @return string The generated refresh token.
     */
    public function generateRefreshToken(array $user, int $expiry): string {
        return $this->jwt->encode([
            'sub' => $user['id'],
            'expiry' => $expiry
        ]);
    }
}
