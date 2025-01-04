<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * The class extends the base `Model` class to provide database operations related to users.
 */
class Airline extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    /**
     * Retrieves a list of airlines from the database with specified limit and offset.
     *
     * @param int $limit The maximum number of airlines to retrieve.
     * @param int $offset The starting point from which to retrieve the airlines.
     * @return bool|array An array of airlines if successful, or false on failure.
     */
    public function getAllAirlines(int $limit, int $offset): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM airlines LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves the total count of airlines from the database.
     *
     * @return int The total number of airlines.
     */
    public function getAirlineCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM airlines");
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Retrieves an airline record from the database based on the provided airline ID.
     *
     * @param int $id The unique identifier of the airline to retrieve.
     * @return array|false An associative array containing the airline data, or false if no record is found.
     */
    public function getAirlineById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM airlines WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Inserts a new airline into the database.
     *
     * @param array $airline An associative array containing the airline's data.
     *                        The key 'name' must be provided as the airline name.
     */
    public function createAirline(array $airline): void {
        $sql = "INSERT INTO airlines (name, logo_path)
                    VALUES (:name, '')";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $airline['name']);
        $stmt->execute();
    }

    /**
     * Updates an airline record in the database.
     *
     * @param int $id The ID of the airline to update.
     * @param array $fields An associative array of fields to update with their new values.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateAirline(int $id, array $fields): bool {
        $setClauses = [];
        foreach ($fields as $field => $value) {
            $setClauses[] = "`$field` = :$field";
        }
        $setClauses = implode(', ', $setClauses);

        $sql = "UPDATE airlines SET $setClauses WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        foreach ($fields as $field => $value) {
            $stmt->bindParam(":$field", $value);
        }
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
