<?php

namespace App\models;

use App\core\Model;
use App\Exceptions\ValidationException;
use PDO;
use PDOException;

/**
 * The class extends the base `Model` class to provide database operations related to planes.
 */
class Plane extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    /**
     * Retrieves a list of planes associated with a specific airline, with optional pagination.
     *
     * @param int $airlineId The unique identifier of the airline whose planes are to be retrieved.
     * @param int $limit The maximum number of planes to retrieve.
     * @param int $offset The starting position for the retrieval of planes.
     *
     * @return bool|array Returns an array of planes if retrieval is successful, or false on failure.
     */
    public function getAllPlanesByAirline(int $airlineId, int $limit, int $offset): bool|array {
        $sql = 'SELECT
                    planes.id AS id,
                    planes.name AS name,
                    planes.configuration AS configuration,
                    airlines.id as airline_id,
                    airlines.name as airline_name
                FROM planes
                    JOIN airlines ON planes.airline_id = airlines.id
                WHERE airlines.id = :airline_id
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves the count of planes associated with a specific airline.
     *
     * @param int $airlineId The unique identifier of the airline for which the plane count is to be retrieved.
     *
     * @return int The number of planes associated with the specified airline.
     */
    public function getPlanesCountByAirline(int $airlineId): int {
        $sql = 'SELECT COUNT(*) as count FROM planes
                    JOIN airlines ON planes.airline_id = airlines.id
                WHERE airlines.id = :airline_id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Retrieves a plane's details from the database using its unique identifier.
     *
     * @param int $id The unique identifier of the plane to retrieve.
     *
     * @return array|null The plane's details as an associative array, or null if no plane is found.
     */
    public function getPlaneById(int $id): ?array {
        $sql = 'SELECT planes.*, airlines.name as airline_name FROM planes
                    JOIN airlines ON planes.airline_id = airlines.id
                WHERE planes.id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new plane record in the database.
     *
     * @param array $plane An associative array containing the plane details:
     */
    public function createPlane(array $plane): void {
        $sql = 'INSERT INTO planes (name, configuration, airline_id)
                    VALUES (:name, :configuration, :airline_id)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $plane['name']);
        $stmt->bindParam(':configuration', $plane['configuration']);
        $stmt->bindParam(':airline_id', $plane['airline_id']);
        $stmt->execute();
    }

    /**
     * Deletes a plane from the database using its unique identifier.
     *
     * @param int $id The unique identifier of the plane to be deleted.
     *
     * @throws PDOException If a database error occurs.
     */
    public function deletePlane(int $id): void {
        $sql = "DELETE FROM planes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
