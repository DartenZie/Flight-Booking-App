<?php

namespace App\controllers;

use App\core\Controller;
use App\models\Airport;
use App\models\Flight;
use App\models\Plane;
use App\models\Statistics;

class StatisticsController extends Controller {
    /**
     * @var Statistics Instance of the Statistics model for data operations.
     */
    private Statistics $statistics;
    /**
     * @var Plane Instance of the Plane model for data operations.
     */
    private Plane $planeModel;
    /**
     * @var Airport Instance of the Airport model for data operations.
     */
    private Airport $airportModel;
    /**
     * @var Flight Instance of the Flight model for data operations.
     */
    private Flight $flightModel;

    /**
     * Initializes controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->statistics = new Statistics($this->db);
        $this->planeModel = new Plane($this->db);
        $this->airportModel = new Airport($this->db);
        $this->flightModel = new Flight($this->db);
    }

    /**
     * Main endpoint handler for statistics.
     * Handles GET requests to fetch statistics of logged in user.
     */
    public function index(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getStatistics()
        ]);
    }

    private function getStatistics(): void {
        $this->authenticateJWTToken();

        $userId = $this->userData['id'];
        $statistics = $this->statistics->getStatisticsData($userId);

        $statistics = array_map(fn($stat) => $this->mapStatistics($stat), $statistics);

        $shortestFlightId = $this->getShortestFlightId($statistics);
        $shortestFlight = $this->flightModel->getFlightById($shortestFlightId);
        $longestFlightId = $this->getLongestFlightId($statistics);
        $longestFlight = $this->flightModel->getFlightById($longestFlightId);

        $this->jsonResponse([
            'statistics' => $statistics,
            'shortestFlight' => $shortestFlight ? $this->mapFlight($shortestFlight) : null,
            'longestFlight' => $longestFlight ? $this->mapFlight($longestFlight) : null,
        ]);
    }

    /**
     * Determines the ID of the shortest flight based on the given statistics.
     *
     * @param array $statistics An array of flight statistics, where each element includes details such as
     *                          departure and arrival coordinates and the flight ID.
     * @return int The ID of the flight with the shortest distance.
     */
    private function getShortestFlightId(array $statistics): int {
        $shortestFlightId = 0;
        $shortestDistance = PHP_INT_MAX;

        foreach ($statistics as $stat) {
            $distance = $this->getFlightDistance(
                $stat['departureLatitude'],
                $stat['departureLongitude'],
                $stat['arrivalLatitude'],
                $stat['arrivalLongitude']
            );

            if ($distance < $shortestDistance) {
                $shortestDistance = $distance;
                $shortestFlightId = $stat['flightId'];
            }
        }

        return $shortestFlightId;
    }

    /**
     * Determines the ID of the longest flight based on provided statistics.
     *
     * @param array $statistics An array of flight statistics, where each entry contains
     *                           flight data including departure and arrival coordinates.
     * @return int The flight ID of the longest flight.
     */
    private function getLongestFlightId(array $statistics): int {
        $longestFlight = 0;
        $longestDistance = 0;

        foreach ($statistics as $stat) {
            $distance = $this->getFlightDistance(
                $stat['departureLatitude'],
                $stat['departureLongitude'],
                $stat['arrivalLatitude'],
                $stat['arrivalLongitude']
            );

            if ($distance > $longestDistance) {
                $longestDistance = $distance;
                $longestFlight = $stat['flightId'];
            }
        }

        return $longestFlight;
    }

    /**
     * Calculates the distance between two geographical coordinates using the Haversine formula.
     *
     * @param float $lat1 Latitude of the first coordinate in degrees.
     * @param float $lon1 Longitude of the first coordinate in degrees.
     * @param float $lat2 Latitude of the second coordinate in degrees.
     * @param float $lon2 Longitude of the second coordinate in degrees.
     * @return int The distance between the two coordinates in kilometers.
     */
    private function getFlightDistance(float $lat1, float $lon1, float $lat2, float $lon2): int {
        $toRadians = function($degree) {
            return ($degree * pi()) / 180;
        };

        $R = 6371;
        $dLat = $toRadians($lat2 - $lat1);
        $dLon = $toRadians($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($toRadians($lat1)) * cos($toRadians($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) ($R * $c);
    }

    private function mapStatistics(array $stats): array {
        return [
            'departureTime' => $stats['departure_time'],
            'arrivalTime' => $stats['arrival_time'],
            'departureAirportId' => $stats['departure_airport_id'],
            'departureLongitude' => $stats['departure_longitude'],
            'departureLatitude' => $stats['departure_latitude'],
            'arrivalAirportId' => $stats['arrival_airport_id'],
            'arrivalLongitude' => $stats['arrival_longitude'],
            'arrivalLatitude' => $stats['arrival_latitude'],
            'airlineId' => $stats['airline_id'],
            'flightId' => $stats['flight_id'],
        ];
    }

    /**
     * Maps a flight record to a detailed response including related entities.
     *
     * @param array $flight The flight record.
     * @return array The mapped flight details.
     */
    private function mapFlight(array $flight): array {
        $plane = $this->planeModel->getPlaneById($flight['plane_id']);

        return [
            'id' => $flight['id'],
            'departureTime' => $flight['departure_time'],
            'arrivalTime' => $flight['arrival_time'],
            'price' => $flight['price'],
            'departureAirport' => $this->airportModel->getAirportById($flight['departure_airport_id']),
            'arrivalAirport' => $this->airportModel->getAirportById($flight['arrival_airport_id']),
            'cancelled' => $flight['cancelled'],
            'plane' => [
                'id' => $plane['id'],
                'name' => $plane['name'],
                'configuration' => $plane['configuration'],
                'airlineId' => $plane['airline_id'],
                'airlineName' => $plane['airline_name']
            ],
        ];
    }
}
