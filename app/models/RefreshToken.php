<?php

namespace App\models;

use App\core\Model;
use PDO;

class RefreshToken extends Model {
    private string $key;

    public function __construct(?PDO $db, string $key) {
        parent::__construct($db);
        $this->key = $key;
    }

    public function create(string $token, int $expiry): bool {
        $hash = hash_hmac('sha256', $token, $this->key);

        $stmt = $this->db->prepare("INSERT INTO refresh_token (hash, expiry) VALUES (:hash, :expiry)");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(string $token): int {
        $hash = hash_hmac('sha256', $token, $this->key);

        $stmt = $this->db->prepare("DELETE FROM refresh_token WHERE hash = :hash");
        $stmt->bindParam(':hash', $hash);

        return $stmt->rowCount();
    }

    public function getByToken(string $token): array | false {
        $hash = hash_hmac('sha256', $token, $this->key);

        $stmt = $this->db->prepare("SELECT * FROM refresh_token WHERE hash = :hash");
        $stmt->bindParam(':hash', $hash);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteExpired(): int {
        $stmt = $this->db->query("DELETE FROM refresh_token WHERE expiry < UNIX_TIMESTAMP()");
        return $stmt->rowCount();
    }
}
