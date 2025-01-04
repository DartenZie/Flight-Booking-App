<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * The class extends the base `Model` class to provide database operations related to users.
 */
class User extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

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
     * Retrieves a list of users with their associated roles based on specified limit and offset.
     *
     * @param int $limit The maximum number of users to retrieve.
     * @param int $offset The number of users to skip before starting to retrieve.
     * @return bool|array Returns an array of users with their roles on success, or false on failure.
     */
    public function getAllUsers(int $limit, int $offset): bool|array {
        $sql = 'SELECT users.*, roles.name as role_name FROM users
                    JOIN roles ON users.role_id = roles.id
                WHERE users.role_id IN (1, 2, 3)
                ORDER BY users.id LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Retrieves the count of users whose role IDs are within the specified range.
     *
     * @return int The count of users with roles matching the specified criteria.
     */
    public function getUsersCount(): int {
        $sql = 'SELECT COUNT(*) as count FROM users
                    JOIN roles ON users.role_id = roles.id
                WHERE users.role_id IN (1, 2, 3)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Retrieves a user's information based on their email address.
     *
     * @param string $email The email address of the user to retrieve.
     * @return array|false An associative array containing the user's information if found,
     *                     or false if no user is found with the provided email.
     */
    public function getUserByEmail(string $email): array | false {
        $sql = 'SELECT users.*, roles.permission_level as permission_level FROM users
                    JOIN roles ON users.role_id = roles.id
                WHERE users.email = :email';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a user's details by their unique ID.
     *
     * @param int $id The unique identifier of the user.
     * @return array|false An associative array containing the user's details and their permission level, or false if no user is found.
     */
    public function getUserById(int $id): array | false {
        $sql = 'SELECT users.*, roles.permission_level as permission_level FROM users
                    JOIN roles ON users.role_id = roles.id
                WHERE users.id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param string $email The email address of the user.
     * @param string $password The user's password, which will be hashed before storing.
     */
    public function createUser(string $firstName, string $lastName, string $email, string $password): void {
        $sql = 'INSERT INTO users (first_name, last_name, email, password, role_id)
                    VALUES (:firstName, :lastName, :email, :password, 1)';

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);

        $stmt->execute();
    }

    /**
     * Updates a user's information in the database based on the provided fields.
     *
     * @param int $id The unique identifier of the user to update.
     * @param array $fields An associative array where the keys are field names and the values are the new values for the corresponding fields.
     * @return bool Returns true if the update was successful, otherwise false.
     */
    public function updateUser(int $id, array $fields): bool {
        $setClauses = [];
        foreach ($fields as $field => $value) {
            $setClauses[] = "`$field` = :$field";
        }
        $setClauses = implode(', ', $setClauses);

        $sql = "UPDATE users SET $setClauses WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        foreach ($fields as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
