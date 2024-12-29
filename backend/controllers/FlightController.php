<?php

require_once 'models/Flight.php';
require_once 'models/Plane.php';
require_once 'models/Airport.php';
require_once 'core/Controller.php';

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
     * Initializes controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->flightModel = new Flight();
        $this->planeModel = new Plane();
        $this->airportModel = new Airport();
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
    public function airplane(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getFlightsByAirline()
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

        $updateData = ['id' => $id];
        $optionalFields = [
            'price' => 'sanitizeString',
            'departureTime' => 'sanitizeDateTime',
            'arrivalTime' => 'sanitizeDateTime',
            'planeId' => 'sanitizeInt',
            'departureAirportId' => 'sanitizeInt',
            'arrivalAirportId' => 'sanitizeInt',
            'cancelled' => 'sanitizeInt',
        ];

        foreach ($optionalFields as $field => $sanitizer) {
            if (isset($data[$field])) {
                $updateData[$field] = InputValidator::$sanitizer($data[$field]);
            }
        }

        $this->flightModel->updateFlight($updateData);

        $this->jsonResponse(['message' => 'Flight updated successfully.', 'updatedFields' => $updateData]);
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
        $departureDate = InputValidator::sanitizeDateTime($data['departureDate']);
        $flightType = $data['type'] ?? 'oneway';
        $returnDate = isset($data['returnDate']) ? InputValidator::sanitizeDateTime($data['returnDate']) : null;

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

        throw new ValidationException('Invalid flight  type. Use "oneway" or "return".', 400);
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
