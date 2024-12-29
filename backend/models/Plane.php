<?php

require_once 'core/Model.php';

/**
 * The class extends the base `Model` class to provide database operations related to planes.
 */
class Plane extends Model {
    public function getAllPlanesByAirline($airlineId, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("
            SELECT
                planes.id AS id,
                planes.name AS name,
                planes.configuration AS configuration
            FROM planes
            JOIN airlines ON planes.airline_id = airlines.id
            WHERE airlines.id = :airline_id
            LIMIT :limit OFFSET :offset;
        ");

        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPlanesCountByAirline($airlineId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM planes
            JOIN airlines ON planes.airline_id = airlines.id
            WHERE airlines.id = :airline_id
        ");

        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    public function getPlaneById($id) {
        $stmt = $this->db->prepare("SELECT planes.*, airlines.name as airline_name FROM planes JOIN airlines ON planes.airline_id = airlines.id WHERE planes.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPlane(array $plane): void {
        $stmt = $this->db->prepare("
            INSERT INTO planes (name, configuration, airline_id)
                VALUES (:name, :configuration, :airline_id);
        ");

        $stmt->bindParam(':name', $plane['name']);
        $stmt->bindParam(':configuration', $plane['configuration']);
        $stmt->bindParam(':airline_id', $plane['airline_id']);
        $stmt->execute();
    }

    public function deletePlane(int $id): void {
        $sql = "DELETE FROM planes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
