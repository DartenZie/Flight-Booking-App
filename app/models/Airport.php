<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * This class provides methods to interact with the airports database.
 */
class Airport extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    /**
     * Retrieves a list of all airports from the database with pagination.
     *
     * @param int $limit The maximum number of airports to retrieve.
     * @param int $offset The number of airports to skip before starting to retrieve the records.
     * @return bool|array Returns an array of airport records if successful, or false on failure.
     */
    public function getAllAirports(int $limit, int $offset): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM airports LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves the total count of airports from the database.
     *
     * This method executes a query to count all rows in the 'airports' table
     * and returns the result as an integer.
     *
     * @return int The total number of airports in the database.
     */
    public function getAirportCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM airports");
        return $stmt->fetch()['count'];
    }

    /**
     * Retrieves the airport details by its unique identifier.
     *
     * @param int $id The unique identifier of the airport.
     * @return array|false The airport details as an associative array, or false if not found.
     */
    public function getAirportById(int $id): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM airports WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Searches for airports in the database using the specified query, limit, and offset.
     *
     * @param string $query The search query to match against the name, city, country, or IATA code of airports.
     * @param int $limit The maximum number of results to retrieve.
     * @param int $offset The starting position of the results to retrieve.
     *
     * @return bool|array Returns an array of matching airport records if successful, or false on failure.
     */
    public function searchAirports(string $query, int $limit, int $offset): bool|array {
        $sql = 'SELECT * FROM airports
                WHERE name LIKE :query
                    OR city LIKE :query
                    OR country LIKE :query
                    OR iata LIKE :query
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        $searchQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $searchQuery);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Searches and returns the total count of airports matching the given query.
     *
     * @param string $query The search query used to match airport name, city, country, or IATA code.
     * @return int The total count of matching airports.
     */
    public function searchAirportCount(string $query): int {
        $sql = 'SELECT COUNT(*) as total FROM airports
                WHERE name LIKE :query
                    OR city LIKE :query
                    OR country LIKE :query
                    OR iata LIKE :query';

        $stmt = $this->db->prepare($sql);
        $searchQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $searchQuery);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
