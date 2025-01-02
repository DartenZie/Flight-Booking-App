<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\Airport;
use App\models\Flight;
use App\models\Plane;
use App\models\Reservation;
use App\utils\InputValidator;

/**
 * Handles all operations related to flights, including CRUD operations,
 * search functionality, and fetching flights by criteria.
 */
class FlightController extends Controller {
    /**
     * @var Flight Instance of the Flight model for data operations.
     */
    private Flight $flightModel;
    /**
     * @var Plane Instance for the Plane model for data operations.
     */
    private Plane $planeModel;
    /**
     * @var Airport Instance for the Airport model for data operations.
     */
    private Airport $airportModel;
    /**
     * @var Reservation Instance for the Reservation model for data operations.
     */
    private Reservation $reservationModel;

    /**
     * Initializes controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->flightModel = new Flight($this->db);
        $this->planeModel = new Plane($this->db);
        $this->airportModel = new Airport($this->db);
        $this->reservationModel = new Reservation($this->db);
    }

    /**
     * Main endpoint handler for flights.
     * Routes requests to appropriate methods based on HTTP request method.
     */
    public function index(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getFlightById(),
            'POST' => fn() => $this->createFlight(),
            'PUT' => fn() => $this->updateFlight(),
        ]);
    }

    /**
     * Endpoint for searching flights.
     * Handles POST requests to search flights based on various criteria.
     */
    public function search(): void {
        $this->handleRequest([
            'POST' => fn() => $this->searchFlights(),
        ]);
    }

    /**
     * Endpoint for fetching flights by airline.
     * Handles GET requests to fetch flights belonging to a specific airline.
     */
    public function airline(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getFlightsByAirline()
        ]);
    }

    /**
     * Endpoint for fetching taken seats by flight.
     * Handles GET requests to fetch taken seats belonging to a specific flight.
     */
    public function seats(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getSeatsByFlight()
        ]);
    }

    /**
     * Creates a new flight.
     * Validates input, sanitizes data, and calls the model to save the flight.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function createFlight(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['price', 'departureTime', 'arrivalTime', 'planeId', 'departureAirportId', 'arrivalAirportId']);
        $createData = [
            'price' => InputValidator::sanitizeString($data['price']),
            'departureTime' => InputValidator::sanitizeDateTime($data['departureTime']),
            'arrivalTime' => InputValidator::sanitizeDateTime($data['arrivalTime']),
            'planeId' => InputValidator::sanitizeInt($data['planeId']),
            'departureAirportId' => InputValidator::sanitizeInt($data['departureAirportId']),
            'arrivalAirportId' => InputValidator::sanitizeInt($data['arrivalAirportId'])
        ];

        $this->flightModel->createFlight($createData);
        $this->jsonResponse(['message' => 'Flight created successfully.'], 201);
    }

    /**
     * Updates an existing flight.
     * Validates input, dynamically builds the update array, and calls the model to save changes.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function updateFlight(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['id']);

        $id = InputValidator::sanitizeInt($data['id']);
        $flight = $this->flightModel->getFlightById($id);
        if (!$flight) {
            throw new ValidationException('Flight not found.', 404);
        }

        $updateData = [];
        $fieldMappings = [
            'price' => 'price',
            'departureTime' => 'departure_time',
            'arrivalTime' => 'arrival_time',
            'planeId' => 'plane_id',
            'departureAirportId' => 'departure_airport_id',
            'arrivalAirportId' => 'arrival_airport_id',
            'cancelled' => 'cancelled'
        ];

        foreach ($fieldMappings as $inputField => $dbField) {
            if (isset($data[$inputField])) {
                $sanitizer = match ($inputField) {
                    'price' => 'sanitizeString',
                    'departureTime', 'arrivalTime' => 'sanitizeDate',
                    'planeId', 'departureAirportId', 'arrivalAirportId' => 'sanitizeInt',
                    'cancelled' => 'sanitizeBool',
                    default => null,
                };
                if ($sanitizer) {
                    $updateData[$dbField] = InputValidator::$sanitizer($data[$inputField]);
                }
            }
        }

        $this->flightModel->updateFlight($id, $updateData);

        $this->jsonResponse(['message' => 'Flight updated successfully.']);
    }

    /**
     * Fetches a flight by ID.
     * Validates input and retrieves the flight details including related entities.
     *
     * @throws ValidationException If validation fails or flight is not found.
     */
    private function getFlightById(): void {
        InputValidator::required($_GET, ['id']);

        $flightId = InputValidator::sanitizeInt($_GET['id']);
        $flight = $this->flightModel->getFlightById($flightId);

        if (!$flight) {
            throw new ValidationException('Flight not found.', 404);
        }

        $flightDetails = $this->mapFlight($flight);
        $this->jsonResponse($flightDetails);
    }

    /**
     * Fetches flights by airline.
     * Supports pagination and retrieves flights belonging to a specific airline.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function getFlightsByAirline(): void {
        InputValidator::required($_GET, ['id']);

        $airlineId = InputValidator::sanitizeInt($_GET['id']);
        $page = InputValidator::sanitizeInt($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $flights = $this->flightModel->getAllFlightsByAirline($airlineId, $limit, $offset) ?? [];
        $totalFlights = $this->flightModel->getFlightsByAirlineCount($airlineId);

        $flights = array_map(fn ($flight) => $this->mapFlight($flight), $flights);

        $this->jsonResponse([
            'flights' => $flights,
            'total' => $totalFlights,
            'page' => $page,
            'totalPages' => ceil($totalFlights / $limit)
        ]);
    }

    /**
     * Fetches taken seats by flight.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function getSeatsByFlight(): void {
        InputValidator::required($_GET, ['id']);

        $flightId = InputValidator::sanitizeInt($_GET['id']);
        $reservations = $this->reservationModel->getAllReservationsByFlight($flightId);

        $seats = array_map(fn ($reservation) => $reservation['seat'], $reservations);

        $this->jsonResponse(['takenSeats' => $seats]);
    }

    /**
     * Searches for flights.
     * Supports both one-way and return flights and validates input accordingly.
     *
     * @throws ValidationException If validation fails or input is incomplete.
     */
    private function searchFlights(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['departureAirportId', 'arrivalAirportId', 'departureDate']);

        $departureAirportId = InputValidator::sanitizeInt($data['departureAirportId']);
        $arrivalAirportId = InputValidator::sanitizeInt($data['arrivalAirportId']);
        $departureDate = InputValidator::sanitizeDate($data['departureDate']);
        $flightType = $data['type'] ?? 'oneway';
        $returnDate = isset($data['returnDate']) ? InputValidator::sanitizeDate($data['returnDate']) : null;

        if ($flightType === 'return' && !$returnDate) {
            throw new ValidationException('Return date is required for return flight searches.', 400);
        }

        $response = $this->getFlightResponse($departureAirportId, $arrivalAirportId, $departureDate, $flightType, $returnDate);

        $this->jsonResponse($response);
    }

    /**
     * Builds the response for flight search based on type (one-way or return).
     *
     * @param int $departureAirportId The departure airport ID.
     * @param int $arrivalAirportId The arrival airport ID.
     * @param string $departureDate The departure date.
     * @param string $flightType The type of flight ('oneway' or 'return').
     * @param string|null $returnDate The return date, if applicable.
     * @return array The search results.
     * @throws ValidationException If the flight type is invalid.
     */
    private function getFlightResponse(
        int $departureAirportId,
        int $arrivalAirportId,
        string $departureDate,
        string $flightType,
        ?string $returnDate
    ): array {
        if ($flightType === 'oneway') {
            $flights = $this->flightModel->searchFlights($departureAirportId, $arrivalAirportId, $departureDate);
            return ['flights' => array_map(fn ($flight) => $this->mapFlight($flight), $flights)];
        }

        if ($flightType === 'return') {
            $departingFlights = $this->flightModel->searchFlights($departureAirportId, $arrivalAirportId, $departureDate);
            $returningFlights = $this->flightModel->searchFlights($arrivalAirportId, $departureAirportId, $returnDate);
            return [
                'departingFlights' => array_map(fn ($flight) => $this->mapFlight($flight), $departingFlights),
                'returningFlights' => array_map(fn ($flight) => $this->mapFlight($flight), $returningFlights),
            ];
        }

        throw new ValidationException('Invalid flight type. Use "oneway" or "return".', 400);
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
