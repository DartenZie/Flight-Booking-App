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

    public function getAllAirlines($limit, $offset): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM airlines LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAirlineCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM airlines");
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    public function getAirlineById($id) {
        $stmt = $this->db->prepare("SELECT * FROM airlines WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createAirline(array $airline): void {
        $stmt = $this->db->prepare("
            INSERT INTO airlines (name, logo_path)
                VALUES (:name, '')
        ");

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
