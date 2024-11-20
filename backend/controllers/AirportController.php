<?php

require_once 'models/Airport.php';
require_once 'core/Controller.php';

class AirportController extends Controller {
    private Airport $airportModel;

    public function __construct() {
        parent::__construct();
        $this->airportModel = new Airport();
    }

    public function index(): void {
        if (isset($_GET['id'])) {
            $this->getAirport($_GET['id']);
        } else {
            $this->getAllAirports();
        }
    }

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

    private function getAirport($id): void {
        $airport = $this->airportModel->getAirportById($id);
        if ($airport) {
            $this->jsonResponse($airport);
        } else {
            $this->jsonResponse(['message' => 'Airport not found'], 404);
        }
    }
}
