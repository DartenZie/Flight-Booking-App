<?php

namespace App\models;

use App\core\Model;
use PDO;

class Reservation extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    public function getReservationById($id) {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllReservationsByUser($userId, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE user_id = :user_id LIMIT :limit OFFSET :offset");

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getReservationsByUserCount($userId): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reservations
            WHERE user_id = :user_id
        ");

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    public function getAllReservationsByFlight($flightId): bool|array {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE flight_id = :flight_id");

        $stmt->bindParam(':flight_id', $flightId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createReservation(array $reservation): bool {
        $stmt = $this->db->prepare("
            INSERT INTO reservations (seat, class, user_id, flight_id)
                VALUES (:seat, :class, :user_id, :flight_id);
        ");

        $stmt->bindParam(':seat', $reservation['seat']);
        $stmt->bindParam(':class', $reservation['class']);
        $stmt->bindParam(':user_id', $reservation['user_id']);
        $stmt->bindParam(':flight_id', $reservation['flight_id']);
        return $stmt->execute();
    }

    public function deleteReservation($id): bool {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
