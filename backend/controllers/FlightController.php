<?php

require_once 'models/Flight.php';
require_once 'models/Plane.php';
require_once 'models/Airport.php';
require_once 'core/Controller.php';

class FlightController extends Controller {
    /**
     * @var Flight Instance of the Flight model for data operations.
     */
    private Flight $flightModel;
    private Plane $planeModel;
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

    public function index(): void {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $this->getFlight(); break;
            case 'POST': $this->postFlight(); break;
            case 'PUT': $this->putFlight(); break;
            default:
                http_response_code(405);
                header('ALLOW: GET, POST, PUT');
                exit();
        }
    }

    private function getFlight(): void {
        if (isset($_GET['id'])) {
            $this->getFlightById($_GET['id']);
        } else if (isset($_GET['airline_id'])) {
            $this->getFlightsByAirline($_GET['airline_id']);
        } else {
            $this->errorResponse('not_found', 404);
        }
    }

    private function postFlight(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $price = $data['price'];
        $departureTime = $data['departureTime'];
        $arrivalTime = $data['arrivalTime'];
        $planeId = $data['planeId'];
        $departureAirportId = $data['departureAirportId'];
        $arrivalAirportId = $data['arrivalAirportId'];

        if (empty($price) || empty($departureTime) || empty($arrivalTime) || empty($planeId) || empty($departureAirportId) || empty($arrivalAirportId)) {
            $this->jsonResponse(['message' => 'All fields are required.'], 400);
            return;
        }

        $this->flightModel->createFlight([
            'price' => $price,
            'departure_time' => $departureTime,
            'arrival_time' => $arrivalTime,
            'plane_id' => $planeId,
            'departure_airport_id' => $departureAirportId,
            'arrival_airport_id' => $arrivalAirportId
        ]);
    }

    private function putFlight(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['id'];
        if (empty($id)) {
            $this->jsonResponse(['message' => 'Field `flightId` is required.'], 400);
            return;
        }

        $flight = $this->flightModel->getFlightById($id);
        if (empty($flight)) {
            $this->jsonResponse(['message' => 'Flight not found.'], 404);
            return;
        }

        $updateData = [
            'id' => $id,
            'price' => $data['price'] ?? $flight['price'],
            'departure_time' => $data['departureTime'] ?? $flight['departure_time'],
            'arrival_time' => $data['arrivalTime'] ?? $flight['arrival_time'],
            'plane_id' => $data['planeId'] ?? $flight['plane_id'],
            'departure_airport_id' => $data['departureAirportId'] ?? $flight['departure_airport_id'],
            'arrival_airport_id' => $data['arrivalAirportId'] ?? $flight['arrival_airport_id'],
            'cancelled' => $data['cancelled'] ?? $flight['cancelled']
        ];

        $this->flightModel->updateFlight($updateData);
        $this->jsonResponse($updateData);
    }

    private function getFlightById(int $id): void {
        $flight = $this->flightModel->getFlightById($id);
        if ($flight) {
            $this->jsonResponse($flight);
        } else {
            $this->jsonResponse(['message' => 'Flight not found'], 404);
        }
    }

    private function getFlightsByAirline(int $id): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $flights = $this->flightModel->getAllFlightsByAirline($id, $limit, $offset) ?? [];
        $totalFlights = $this->flightModel->getFlightsByAirlineCount($id);

        $flights = array_map(fn($flight) => [
            'id' => $flight['id'],
            'price' => $flight['price'],
            'departureTime' => $flight['departure_time'],
            'arrivalTime' => $flight['arrival_time'],
            'cancelled' => (bool)$flight['cancelled'],
            'plane' => $this->getPlaneById($flight['plane_id']),
            'departureAirport' => $this->airportModel->getAirportById($flight['departure_airport_id']),
            'arrivalAirport' => $this->airportModel->getAirportById($flight['arrival_airport_id'])
        ], $flights);

        $this->jsonResponse([
            'flights' => $flights,
            'total' => $totalFlights,
            'page' => $page,
            'totalPages' => ceil($totalFlights / $limit)
        ]);
    }

    private function getPlaneById(int $id): array {
        $plane = $this->planeModel->getPlaneById($id);

        return [
            'id' => $plane['id'],
            'name' => $plane['name'],
            'configuration' => $plane['configuration'],
            'airlineName' => $plane['airline_name']
        ];
    }
}
