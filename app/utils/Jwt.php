<?php

namespace App\utils;

use Exception;
use InvalidArgumentException;

/**
 * This class provides a simple implementation of JSON Web Tokens (JWT) using HMAC SHA-256 for signing.
 * It supports encoding payload data into a JWT and decoding a JWT to verify its signature and extract the payload.
 */
class Jwt {
    /**
     * @var string The secret key used for signing and verifying JWTs.
     */
    private string $key;

    public function __construct(string $key) {
        $this->key = $key;
    }

    /**
     * Encodes an array payload into a JWT string.
     *
     * @param array $payload The payload data to encode.
     * @return string The generated JWT string.
     */
    public function encode(array $payload): string {
        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);

        $header = $this->base64UrlEncode($header);
        $payload = json_encode($payload);
        $payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $header . "." . $payload, $this->key, true);
        $signature = $this->base64UrlEncode($signature);
        return $header . "." . $payload . "." . $signature;
    }

    /**
     * Decodes a JWT string and returns the payload.
     *
     * Verifies the token's signature using the secret key. If the signature is invalid or the token
     * is malformed, an exception is thrown.
     *
     * @param string $token The JWT string to decode.
     * @return array The decoded payload data.
     * @throws InvalidArgumentException If the token is invalid or the signature does not match.
     * @throws Exception Token expired.
     */
    public function decode(string $token): array {
        if (
            preg_match(
                "/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
                $token,
                $matches
            ) !== 1
        ) {
            throw new InvalidArgumentException("Invalid token");
        }

        $signature = hash_hmac('sha256', $matches['header'] . '.' . $matches['payload'], $this->key, true);
        $signature_from_token = $this->base64UrlDecode($matches['signature']);

        if (!hash_equals($signature, $signature_from_token)) {
            throw new InvalidArgumentException;
        }

        $payload = json_decode($this->base64UrlDecode($matches["payload"]), true);

        if ($payload['expiry'] < time()) {
            // TODO token expired exception
            throw new Exception("token_expired");
        }

        return $payload;
    }

    /**
     * Encodes data using Base64 URL encoding (URL-safe)
     *
     * @param string $data The data to encode.
     * @return array|string The URL-safe Base64 encoded string.
     */
    private function base64UrlEncode(string $data): array|string {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Decodes a Base64 URL-encoded string.
     *
     * @param string $data The URL-safe Base64 encoded string to decode.
     * @return array|string The decoded data.
     */
    private function base64UrlDecode(string $data): array|string {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
