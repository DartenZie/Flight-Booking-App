<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * The class extends the base `Model` class to provide database operations related to reservations.
 */
class Reservation extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    /**
     * Retrieves a reservation record from the database based on the provided ID.
     *
     * @param int $id The ID of the reservation to retrieve.
     * @return array|false The reservation data as an associative array, or false if no record is found.
     */
    public function getReservationById(int $id): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all reservation records associated with a specific user.
     *
     * @param int $userId The ID of the user whose reservations are to be fetched.
     * @param int $limit The maximum number of results to return.
     * @param int $offset The number of records to skip before retrieving results.
     * @return bool|array Returns an array of reservations if successful, or false on failure.
     */
    public function getAllReservationsByUser(int $userId, int $limit, int $offset): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE user_id = :user_id LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves the count of reservations made by a specific user.
     *
     * @param int $userId The ID of the user whose reservation count is to be retrieved.
     * @return int The total number of reservations made by the specified user.
     */
    public function getReservationsByUserCount(int $userId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM reservations WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Retrieves all reservations associated with a specific flight.
     *
     * @param int $flightId The ID of the flight for which reservations are to be fetched.
     * @return bool|array Returns an array of reservations if found, or false on failure.
     */
    public function getAllReservationsByFlight(int $flightId): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE flight_id = :flight_id");
        $stmt->bindParam(':flight_id', $flightId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Creates a new reservation in the database.
     *
     * @param array $reservation An associative array containing reservation details, including:
     * @return bool Returns true if the reservation was successfully created, false otherwise.
     */
    public function createReservation(array $reservation): bool {
        $sql = 'INSERT INTO reservations (seat, class, user_id, flight_id)
                    VALUES (:seat, :class, :user_id, :flight_id)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':seat', $reservation['seat']);
        $stmt->bindParam(':class', $reservation['class']);
        $stmt->bindParam(':user_id', $reservation['user_id']);
        $stmt->bindParam(':flight_id', $reservation['flight_id']);
        return $stmt->execute();
    }

    /**
     * Deletes a reservation from the database based on the given reservation ID.
     *
     * @param int $id The unique identifier of the reservation to delete.
     * @return bool Returns true on successful deletion, or false on failure.
     */
    public function deleteReservation(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
