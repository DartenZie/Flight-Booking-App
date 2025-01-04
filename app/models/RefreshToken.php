<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * Manages the creation, deletion, and retrieval of refresh tokens in a database.
 */
class RefreshToken extends Model {
    /**
     * Represents a variable or parameter used for storing a value, which could be of any data type.
     */
    private string $key;

    /**
     * Constructor method for initializing the class.
     *
     * @param PDO|null $db The PDO database connection instance or null.
     * @param string $key The key used for hashing or encryption.
     *
     * @return void
     */
    public function __construct(?PDO $db, string $key) {
        parent::__construct($db);
        $this->key = $key;
    }

    /**
     * Creates a new refresh token with the given token and expiry time.
     *
     * @param string $token The token to be hashed and stored.
     * @param int $expiry The expiry time (in seconds) for the token.
     * @return bool Returns true on successful insertion, false otherwise.
     */
    public function create(string $token, int $expiry): bool {
        $hash = hash_hmac('sha256', $token, $this->key);

        $stmt = $this->db->prepare("INSERT INTO refresh_token (hash, expiry) VALUES (:hash, :expiry)");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deletes a record from the refresh_token table based on the provided token.
     *
     * @param string $token The token used to identify the record to delete.
     * @return int The number of rows affected by the delete operation.
     */
    public function delete(string $token): int {
        $hash = hash_hmac('sha256', $token, $this->key);

        $stmt = $this->db->prepare("DELETE FROM refresh_token WHERE hash = :hash");
        $stmt->bindParam(':hash', $hash);

        return $stmt->rowCount();
    }

    /**
     * Retrieves a record associated with the provided token from the database.
     *
     * @param string $token The token used to identify the record in the database.
     * @return array|false An associative array containing the record data if found, or false if no record matches the token.
     */
    public function getByToken(string $token): array | false {
        $hash = hash_hmac('sha256', $token, $this->key);

        $stmt = $this->db->prepare("SELECT * FROM refresh_token WHERE hash = :hash");
        $stmt->bindParam(':hash', $hash);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Deletes expired tokens from the database.
     *
     * @return int The number of rows affected by the delete operation.
     */
    public function deleteExpired(): int {
        $stmt = $this->db->query("DELETE FROM refresh_token WHERE expiry < UNIX_TIMESTAMP()");
        return $stmt->rowCount();
    }
}
