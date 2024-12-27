<?php

require_once 'core/Model.php';

/**
 * The class extends the base `Model` class to provide database operations related to users.
 */
class Airline extends Model {
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
            INSERT INTO airlines (name)
                VALUES (:name)
        ");

        $stmt->bindParam(':name', $airline['name']);
        $stmt->execute();
    }
}
