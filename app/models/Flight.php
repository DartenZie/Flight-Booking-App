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

    /**
     * Retrieves a list of flights belonging to a specific airline with pagination.
     *
     * @param int $airlineId The ID of the airline whose flights are to be retrieved.
     * @param int $limit The maximum number of flights to retrieve.
     * @param int $offset The offset for the first flight to retrieve.
     *
     * @return bool|array Returns an array of flights for the specified airline, or false on failure.
     */
    public function getAllFlightsByAirline(int $airlineId, int $limit, int $offset): bool|array {
        $sql = 'SELECT
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
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves the count of flights associated with a specific airline.
     *
     * @param int $airlineId The ID of the airline for which the flight count is to be retrieved.
     *
     * @return int The total number of flights associated with the specified airline.
     */
    public function getFlightsByAirlineCount(int $airlineId): int {
        $sql = 'SELECT COUNT(*) as count FROM flights
                    JOIN planes ON flights.plane_id = planes.id
                    JOIN airlines ON planes.airline_id = airlines.id
                WHERE airline_id = :airline_id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':airline_id', $airlineId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Fetches the flight details for a given flight ID.
     *
     * @param int $id The unique identifier of the flight.
     * @return array|null An associative array containing the flight details, or null if no flight is found.
     */
    public function getFlightById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM flights WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new flight record in the database.
     *
     * @param array $flight An associative array containing flight details.
     */
    public function createFlight(array $flight): void {
        $sql = 'INSERT INTO flights (price, departure_time, arrival_time, plane_id, departure_airport_id, arrival_airport_id, cancelled)
                    VALUES (:price, :departure_time, :arrival_time, :plane_id, :departure_airport_id, :arrival_airport_id, false)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':price', $flight['price']);
        $stmt->bindParam(':departure_time', $flight['departureTime']);
        $stmt->bindParam(':arrival_time', $flight['arrivalTime']);
        $stmt->bindParam(':plane_id', $flight['planeId'], PDO::PARAM_INT);
        $stmt->bindParam(':departure_airport_id', $flight['departureAirportId'], PDO::PARAM_INT);
        $stmt->bindParam(':arrival_airport_id', $flight['arrivalAirportId'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Updates the flight record in the database with the specified fields and values.
     *
     * @param int $id The unique identifier of the flight to update.
     * @param array $fields An associative array of field names and their corresponding values to update.
     * @return bool Returns true on successful update, or false on failure.
     */
    public function updateFlight(int $id, array $fields): bool {
        $setClauses = [];
        foreach ($fields as $field => $value) {
            $setClauses[] = "{$field} = :{$field}";
        }
        $setClauses = implode(', ', $setClauses);

        $sql = "UPDATE flights SET {$setClauses} WHERE id = :id;";
        $stmt = $this->db->prepare($sql);

        foreach ($fields as $field => $value) {
            $stmt->bindParam(":$field", $value);
        }
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Searches for flights based on departure airport, arrival airport, and date.
     *
     * @param int $departureAirportId The ID of the departure airport.
     * @param int $arrivalAirportId The ID of the arrival airport.
     * @param string $date The date of the flight in 'YYYY-MM-DD' format.
     *
     * @return array Returns an array of flights that match the given criteria.
     */
    public function searchFlights(int $departureAirportId, int $arrivalAirportId, string $date): array {
        $sql = 'SELECT * FROM flights
                WHERE departure_airport_id = :departureAirportId
                    AND arrival_airport_id = :arrivalAirportId
                    AND DATE(departure_time) = :date
                    AND cancelled = 0';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':departureAirportId' => $departureAirportId,
            ':arrivalAirportId' => $arrivalAirportId,
            ':date' => $date
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
