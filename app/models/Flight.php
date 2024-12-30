<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * The class extends the base `Model` class to provide database operations related to planes.
 */
class Flight extends Model {
    public function __construct(?PDO $db) {
        parent::__construct($db);
    }

    public function getAllFlightsByAirline($airlineId, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("
            SELECT
                flights.id AS id,
                flights.price AS price,
                flights.departure_time AS departure_time,
                flights.arrival_time AS arrival_time,
                flights.cancelled AS cancelled,
                flights.plane_id AS plane_id,
                flights.departure_airport_id as departure_airport_id,
                flights.arrival_airport_id as arrival_airport_id
            FROM flights
            JOIN planes ON flights.plane_id = planes.id
            JOIN airlines ON planes.airline_id = airlines.id
            WHERE airline_id = :airline_id
            LIMIT :limit OFFSET :offset;
        ");

        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFlightsByAirlineCount($airlineId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM flights
            JOIN planes ON flights.plane_id = planes.id
            JOIN airlines ON planes.airline_id = airlines.id
            WHERE airline_id = :airline_id;
        ");

        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    public function getFlightById($id) {
        $stmt = $this->db->prepare("SELECT * FROM flights WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createFlight(array $flight): void {
        $stmt = $this->db->prepare("
            INSERT INTO flights (price, departure_time, arrival_time, plane_id, departure_airport_id, arrival_airport_id, cancelled)
                VALUES (:price, :departure_time, :arrival_time, :plane_id, :departure_airport_id, :arrival_airport_id, false);
        ");

        $stmt->bindParam(':price', $flight['price']);
        $stmt->bindParam(':departure_time', $flight['departureTime']);
        $stmt->bindParam(':arrival_time', $flight['arrivalTime']);
        $stmt->bindParam(':plane_id', $flight['planeId'], PDO::PARAM_INT);
        $stmt->bindParam(':departure_airport_id', $flight['departureAirportId'], PDO::PARAM_INT);
        $stmt->bindParam(':arrival_airport_id', $flight['arrivalAirportId'], PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateFlight(int $id, array $fields): bool {
        $setClauses = [];
        foreach ($fields as $field => $value) {
            $setClauses[] = "{$field} = :{$field}";
        }
        $setClauses = implode(', ', $setClauses);

        $sql = "UPDATE flights SET {$setClauses} WHERE id = :id;";
        $stmt = $this->db->prepare($sql);

        foreach ($fields as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function searchFlights(int $departureAirportId, int $arrivalAirportId, string $date): array {
        $sql = 'SELECT * FROM flights WHERE
                departure_airport_id = :departureAirportId
                AND arrival_airport_id = :arrivalAirportId
                AND DATE(departure_time) = :date';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':departureAirportId' => $departureAirportId,
            ':arrivalAirportId' => $arrivalAirportId,
            ':date' => $date
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
