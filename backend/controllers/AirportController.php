<?php

require_once 'models/Airport.php';
require_once 'core/Controller.php';

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
        $this->airportModel = new Airport();
    }

    /**
     * Handles the default action. If an 'id' parameter is provided, it fetches a
     * specific airport; otherwise , it retrieves all airports.
     *
     * @return void
     */
    public function index(): void {
        if (isset($_GET['id'])) {
            $this->getAirport($_GET['id']);
        } else {
            $this->getAllAirports();
        }
    }

    /**
     * Handles airport search requests based on a query string (`q` parameter).
     * Supports pagination with the `page` parameter.
     *
     * @return void
     */
    public function search(): void {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        if (empty($query)) {
            $this->jsonResponse(['message' => 'Search query cannot be empty'], 400);
        }

        $airports = $this->airportModel->searchAirports($query, $limit, $offset);
        $totalAirports = $this->airportModel->searchAirportCount($query);

        $this->jsonResponse([
            'airports' => $airports,
            'total' => $totalAirports,
            'page' => $page,
            'totalPages' => ceil($totalAirports / $limit)
        ]);
    }

    /**
     * Retrieves a paginated list of all airports.
     * Supports pagination with the `page` parameter.
     *
     * @return void
     */
    private function getAllAirports(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $airports = $this->airportModel->getAllAirports($limit, $offset);
        $totalAirports = $this->airportModel->getAirportCount();

        $this->jsonResponse([
            'airports' => $airports,
            'total' => $totalAirports,
            'page' => $page,
            'totalPages' => ceil($totalAirports / $limit)
        ]);
    }

    /**
     * Fetches details of a specific airport based on its ID.
     *
     * @param int $id The ID of the airport to retrieve.
     * @return void
     */
    private function getAirport(int $id): void {
        $airport = $this->airportModel->getAirportById($id);
        if ($airport) {
            $this->jsonResponse($airport);
        } else {
            $this->jsonResponse(['message' => 'Airport not found'], 404);
        }
    }
}
