<?php
require_once 'models/Airline.php';
require_once 'core/Controller.php';

class AirlineController extends Controller {
    /**
     * @var Airline Instance of the Airline model for data operations.
     */
    private Airline $airlineModel;

    /**
     * Initializes the controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->airlineModel = new Airline();
    }

    public function index(): void {
        $this->authenticateJWTToken('flightManager');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $this->getAirline(); break;
            case 'POST': $this->postAirline(); break;
            default:
                http_response_code(405);
                header('ALLOW: GET, POST');
                exit();
        }
    }

    private function getAirline(): void {
        if (isset($_GET['id'])) {
            $this->getAirlineById($_GET['id']);
        } else {
            $this->getAllAirlines();
        }
    }

    private function postAirline(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['name'];

        if (empty($name)) {
            $this->jsonResponse(['message' => 'All fields are required.'], 400);
            return;
        }

        $this->airlineModel->createAirline(['name' => $name]);
    }

    private function getAllAirlines(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $airlines = $this->airlineModel->getAllAirlines($limit, $offset);
        $totalAirlines = $this->airlineModel->getAirlineCount();

        $this->jsonResponse([
            'airlines' => $airlines,
            'total' => $totalAirlines,
            'page' => $page,
            'totalPages' => ceil($totalAirlines / $limit)
        ]);
    }

    private function getAirlineById(int $id): void {
        $airline = $this->airlineModel->getAirlineById($id);
        if ($airline) {
            $this->jsonResponse($airline);
        } else {
            $this->jsonResponse(['message' => 'Airline not found'], 404);
        }
    }
}
