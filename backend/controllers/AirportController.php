<?php

require_once 'models/Airport.php';
require_once 'core/Controller.php';

class AirportController extends Controller {
    private $airportModel;

    public function __construct() {
        $this->airportModel = new Airport();
    }

    public function index() {
        if (isset($_GET['id'])) {
            $this->getAirport($_GET['id']);
        } else {
            $this->getAllAirports();
        }
    }

    public function search() {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        if (empty($query)) {
            $this->jsonResponse(['message' => 'Search query cannot be empty'], 400);
            return;
        }

        // Perform the search
        $airports = $this->airportModel->searchAirports($query, $limit, $offset);
        $totalAirports = $this->airportModel->searchAirportCount($query);

        $this->jsonResponse([
            'airports' => $airports,
            'total' => $totalAirports,
            'page' => $page,
            'totalPages' => ceil($totalAirports / $limit)
        ]);
    }

    private function getAllAirports() {
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

    private function getAirport($id) {
        $airport = $this->airportModel->getAirportById($id);
        if ($airport) {
            $this->jsonResponse($airport);
        } else {
            $this->jsonResponse(['message' => 'Airport not found'], 404);
        }
    }
}
