<?php

require_once 'core/Model.php';

/**
 * The class extends the base `Model` class to provide database operations related to planes.
 */
class Flight extends Model {
    public function getAllFlightsByAirline($airlineId, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("
            SELECT
                flights.id AS id,
                flights.price AS price,
                flights.departure_time AS departure_time,
                flights.arrival_time AS arrival_time,
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

    public function getAllFlightsByDepartureAirport($departure_airport_id, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("
            SELECT
                flights.id AS id,
                flights.price AS price
            FROM flights
            WHERE departure_airport_id = :departure_airport_id
            LIMIT :limit OFFSET :offset;
        ");

        $stmt->bindParam(':departure_airport_id', $departure_airport_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllFlightsByArrivalAirport($arrivalAirportId, $limit, $offset): bool|array {
        $stmt = $this->db->prepare("
            SELECT
                flights.id AS id,
                flights.price AS price
            FROM flights
            WHERE arrival_airport_id = :arrival_airport_id
            LIMIT :limit OFFSET :offset;
        ");

        $stmt->bindParam(':arrival_airport_id', $arrivalAirportId, PDO::PARAM_INT);
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

    public function getFlightsByDepartureAirportCount($departureAirportId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM flights
            WHERE departure_airport_id = :departure_airport_id;
        ");

        $stmt->bindParam(':departure_airport_id', $departureAirportId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    public function getFlightsByArrivalAirportCount($arrivalAirportId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM flights
            WHERE arrival_airport_id = :arrival_airport_id;
        ");

        $stmt->bindParam(':arrival_airport_id', $arrivalAirportId, PDO::PARAM_INT);
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
            INSERT INTO flights (price, departure_time, arrival_time, plane_id, departure_airport_id, arrival_airport_id)
                VALUES (:price, :departure_time, :arrival_time, :plane_id, :departure_airport_id, :arrival_airport_id);
        ");

        $stmt->bindParam(':price', $flight['price']);
        $stmt->bindParam(':departure_time', $flight['departure_time']);
        $stmt->bindParam(':arrival_time', $flight['arrival_time']);
        $stmt->bindParam(':plane_id', $flight['plane_id'], PDO::PARAM_INT);
        $stmt->bindParam(':departure_airport_id', $flight['departure_airport_id'], PDO::PARAM_INT);
        $stmt->bindParam(':arrival_airport_id', $flight['arrival_airport_id'], PDO::PARAM_INT);
        $stmt->execute();
    }
}
