<?php

namespace App\models;

use App\core\Model;
use PDO;

/**
 * The class extends the base `Model` class to provide database operations related to statistics.
 */
class Statistics extends Model {
    public function __construct(?\PDO $db) {
        parent::__construct($db);
    }

    public function getStatisticsData($userId): bool|array {
        $stmt = $this->db->prepare("
            WITH RankedFlights AS (
                SELECT
                    departure_time,
                    arrival_time,
                    departure_airport_id,
                    departure_airport.latitude AS departure_latitude,
                    departure_airport.longitude AS departure_longitude,
                    arrival_airport_id,
                    arrival_airport.latitude AS arrival_latitude,
                    arrival_airport.longitude AS arrival_longitude,
                    planes.airline_id AS airline_id,
                    flights.id AS flight_id,
                    ROW_NUMBER() OVER (PARTITION BY flights.id ORDER BY departure_time) AS rn
                FROM reservations
                         JOIN flights ON reservations.flight_id = flights.id
                         JOIN airports AS departure_airport ON flights.departure_airport_id = departure_airport.id
                         JOIN airports AS arrival_airport ON flights.arrival_airport_id = arrival_airport.id
                         JOIN planes ON flights.plane_id = planes.id
                WHERE user_id = :user_id
            )
            SELECT
                departure_time,
                arrival_time,
                departure_airport_id,
                departure_latitude,
                departure_longitude,
                arrival_airport_id,
                arrival_latitude,
                arrival_longitude,
                airline_id,
                flight_id
            FROM RankedFlights
            WHERE rn = 1;
        ");

        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
