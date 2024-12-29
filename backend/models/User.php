<?php

require_once 'core/Model.php';

/**
 * The class extends the base `Model` class to provide database operations related to users.
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

    public function getAllUsers($limit, $offset): bool|array {
        $stmt = $this->db->prepare('SELECT users.*, roles.name as role_name FROM users JOIN roles ON users.role_id = roles.id LIMIT :limit OFFSET :offset');
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getUsersCount(): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM users');
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Retrieves a user's details by their email address
     * @param $email
     * @return array|false
     */
    public function getUserByEmail($email): array | false {
        $stmt = $this->db->prepare('SELECT users.*, roles.permission_level as permission_level FROM users JOIN roles ON users.role_id = roles.id WHERE users.email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $id): array | false {
        $stmt = $this->db->prepare("SELECT users.*, roles.permission_level as permission_level FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($firstName, $lastName, $email, $password): void {
        $stmt = $this->db->prepare("
            INSERT INTO users (firstName, lastName, email, password, role_id)
                VALUES (:firstName, :lastName, :email, :password, 1)
        ");

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        $stmt->execute();
    }

    public function updateUser(array $user): void {
        $stmt = $this->db->prepare("UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email, nationality = :nationality, dateOfBirth = :dateOfBirth, phone = :phone, sex = :sex WHERE id = :id");

        $stmt->bindParam(':id', $user['id']);
        $stmt->bindParam(':firstName', $user['firstName']);
        $stmt->bindParam(':lastName', $user['lastName']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':nationality', $user['nationality']);
        $stmt->bindParam(':dateOfBirth', $user['dateOfBirth']);
        $stmt->bindParam(':phone', $user['phone']);
        $stmt->bindParam(':sex', $user['sex']);

        $stmt->execute();
    }
}
