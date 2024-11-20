<?php

require_once 'core/Model.php';

/**
 * The class extends the base `Model` class to provide database operations related to users.
 * It includes methods for checking user existence, retrieving user data by email, and creating new users.
 */
class User extends Model {
    /**
     * Checks if a user with the given email address exists in the database.
     *
     * @param string $email The email address to check for.
     * @return bool Returns `true` if a user with the specified email exists, `false` otherwise.
     */
    public function userExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    /**
     * Retrieves a user's details by their email address
     * @param $email
     * @return array|false
     */
    public function getUserByEmail($email): array | false {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $id): array | false {
        $stmt = $this->db->prepare("SELECT id, email, firstName, lastName FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($firstName, $lastName, $email, $password): void {
        $stmt = $this->db->prepare("
            INSERT INTO users (firstName, lastName, email, password)
                VALUES (:firstName, :lastName, :email, :password)
        ");

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        $stmt->execute();
    }
}
