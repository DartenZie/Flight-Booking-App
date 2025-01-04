<?php

namespace App\utils;

use App\models\Airport;
use App\models\Plane;

/**
 * Utility class providing static methods for mapping data between different representations.
 */
class MapperUtils {
    /**
     * Maps the airport data from the input array to a structured format.
     *
     * @param array $airport The input array containing airport data.
     * @return array The structured array containing mapped airport data.
     */
    public static function mapAirport(array $airport): array {
        return [
            'id' => $airport['id'],
            'name' => $airport['name'],
            'city' => $airport['city'],
            'country' => $airport['country'],
            'iata' => $airport['iata'],
            'latitude' => $airport['latitude'],
            'longitude' => $airport['longitude'],
            'timezone' => $airport['timezone']
        ];
    }

    /**
     * Maps an airline array to a formatted array with specific keys.
     *
     * @param array $airline An associative array containing airline data with original keys.
     * @return array A formatted associative array containing mapped airline data.
     */
    public static function mapAirline(array $airline): array {
        return [
            'id' => $airline['id'],
            'name' => $airline['name']
        ];
    }

    /**
     * Maps a user array to a formatted array with specific keys.
     *
     * @param array $user An associative array containing user data with original keys.
     * @return array A formatted associative array containing mapped user data.
     */
    public static function mapCurrentUser(array $user): array {
        return [
            'id' => $user['id'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'email' => $user['email'],
            'nationality' => $user['nationality'],
            'dateOfBirth' => $user['date_of_birth'],
            'phone' => $user['phone'],
            'sex' => $user['sex'],
            'permissionLevel' => $user['permission_level'],
            'createdAt' => $user['created_at']
        ];
    }

    /**
     * Transforms a user array into a structured format with specific keys.
     *
     * @param array $user An associative array representing raw user data with its original keys.
     * @return array A mapped associative array containing structured user information.
     */
    public static function mapUser(array $user): array {
        return [
            'id' => $user['id'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'email' => $user['email'],
            'role' => $user['role_name'],
        ];
    }

    /**
     * Maps a plane's details from the input array to a predefined structure.
     *
     * @param array $plane An associative array containing the plane's details.
     * @return array An array containing the mapped plane details.
     */
    public static function mapPlane(array $plane): array {
        return [
            'id' => $plane['id'],
            'name' => $plane['name'],
            'configuration' => $plane['configuration'],
            'airlineId' => $plane['airline_id'],
            'airlineName' => $plane['airline_name'],
        ];
    }

    /**
     * Maps flight details from raw data and associated models into a structured array.
     *
     * @param array $flight The associative array containing raw flight data.
     * @param Plane $planeModel The instance of the Plane model used to fetch plane data.
     * @param Airport $airportModel The instance of the Airport model used to fetch airport data.
     * @return array Returns a structured array containing mapped flight details.
     */
    public static function mapFlight(array $flight, Plane $planeModel, Airport $airportModel): array {
        return [
            'id' => $flight['id'],
            'departureTime' => $flight['departure_time'],
            'arrivalTime' => $flight['arrival_time'],
            'price' => $flight['price'],
            'departureAirport' => self::mapAirport($airportModel->getAirportById($flight['departure_airport_id'])),
            'arrivalAirport' => self::mapAirport($airportModel->getAirportById($flight['arrival_airport_id'])),
            'cancelled' => $flight['cancelled'],
            'plane' => self::mapPlane($planeModel->getPlaneById($flight['plane_id'])),
        ];
    }

    /**
     * Maps the input statistic array to a structured array with specific keys.
     *
     * @param array $statistic An associative array containing flight statistic data.
     * @return array An associative array with mapped keys.
     */
    public static function mapStatistic(array $statistic): array {
        return [
            'departureTime' => $statistic['departure_time'],
            'arrivalTime' => $statistic['arrival_time'],
            'departureAirportId' => $statistic['departure_airport_id'],
            'departureLongitude' => $statistic['departure_longitude'],
            'departureLatitude' => $statistic['departure_latitude'],
            'arrivalAirportId' => $statistic['arrival_airport_id'],
            'arrivalLongitude' => $statistic['arrival_longitude'],
            'arrivalLatitude' => $statistic['arrival_latitude'],
            'airlineId' => $statistic['airline_id'],
            'flightId' => $statistic['flight_id'],
        ];
    }
}
