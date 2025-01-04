<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\Airport;
use App\utils\InputValidator;
use App\utils\MapperUtils;

/**
 * Handles requests related to airport data, including retrieving all airports,
 * searching for airports, and fetching specific airport details.
 */
class AirportController extends Controller {
    /**
     * @var Airport Instance of the Airport model for data operations.
     */
    private Airport $airportModel;

    /**
     * Initializes the controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->airportModel = new Airport($this->db);
    }

    /**
     * Main endpoint handler for airports.
     */
    public function index(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getAirportById()
        ]);
    }

    /**
     * Endpoint for fetching all airports.
     */
    public function list(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getAllAirports()
        ]);
    }

    /**
     * Endpoint for searching airports.
     */
    public function search(): void {
        $this->handleRequest([
            'GET' => fn() => $this->searchAirports()
        ]);
    }

    /**
     * Fetches an airport by ID.
     * Validates input and retrieves the airport details.
     *
     * @throws ValidationException If validation fails or airport is not found.
     */
    private function getAirportById(): void {
        InputValidator::required($_GET, ['id']);

        $airportId = InputValidator::sanitizeInt($_GET['id']);
        $airport = $this->airportModel->getAirportById($airportId);

        if (!$airport) {
            throw new ValidationException('Airport not found.', 404);
        }

        $airportDetails = MapperUtils::mapAirport($airport);
        $this->jsonResponse($airportDetails);
    }

    /**
     * Fetches all airports.
     * Supports pagination and retrieves all airports.
     */
    private function getAllAirports(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $airports = $this->airportModel->getAllAirports($limit, $offset) ?? [];
        $totalAirports = $this->airportModel->getAirportCount();

        $airports = array_map(fn ($airport) => MapperUtils::mapAirport($airport), $airports);

        $this->jsonResponse([
            'airports' => $airports,
            'total' => $totalAirports,
            'page' => $page,
            'totalPages' => ceil($totalAirports / $limit)
        ]);
    }

    /**
     * Handles airport search requests based on a query string (`q` parameter).
     * Supports pagination with the `page` parameter.
     *
     * @throws ValidationException If validation fails or airport is not found.
     */
    public function searchAirports(): void {
        InputValidator::required($_GET, ['q']);
        $query = InputValidator::sanitizeString($_GET['q']);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $airports = $this->airportModel->searchAirports($query, $limit, $offset);
        $totalAirports = $this->airportModel->searchAirportCount($query);

        $airports = array_map(fn ($airport) => MapperUtils::mapAirport($airport), $airports);

        $this->jsonResponse([
            'airports' => $airports,
            'total' => $totalAirports,
            'page' => $page,
            'totalPages' => ceil($totalAirports / $limit)
        ]);
    }
}
