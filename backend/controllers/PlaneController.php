<?php

require_once 'models/Plane.php';
require_once 'core/Controller.php';

class PlaneController extends Controller {
    /**
     * @var Plane Instance of the Plane model for data operations.
     */
    private Plane $planeModel;

    /**
     * Initializes controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->planeModel = new Plane();
    }

    public function index(): void {
        $this->authenticateJWTToken('flightManager');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $this->getPlane(); break;
            case 'POST': $this->postPlane(); break;
            default:
                http_response_code(405);
                header('ALLOW: GET, POST');
                exit();
        }
    }

    private function getPlane(): void {
        if (isset($_GET['id'])) {
            $this->getPlaneById($_GET['id']);
        } else if (isset($_GET['airline_id'])) {
            $this->getAllPlanesByAirline($_GET['airline_id']);
        } else {
            $this->errorResponse('not_found', 404);
        }
    }

    private function postPlane(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['name'];
        $configuration = $data['configuration'];
        $airlineId = $data['airline_id'];

        if (empty($name) || empty($configuration) || empty($airlineId)) {
            $this->jsonResponse(['message' => 'All fields are required.'], 400);
            return;
        }

        $this->planeModel->createPlane([
            'name' => $name,
            'configuration' => $configuration,
            'airline_id' => $airlineId
        ]);
    }

    private function getAllPlanesByAirline(int $airlineId): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $planes = $this->planeModel->getAllPlanesByAirline($airlineId, $limit, $offset);
        $totalPlanes = $this->planeModel->getPlanesCountByAirline($airlineId);

        $this->jsonResponse([
            'planes' => $planes,
            'total' => $totalPlanes,
            'page' => $page,
            'totalPages' => ceil($totalPlanes / $limit)
        ]);
    }

    private function getPlaneById(int $id): void {
        $plane = $this->planeModel->getPlaneById($id);
        if ($plane) {
            $this->jsonResponse($plane);
        } else {
            $this->jsonResponse(['message' => 'Plane not found'], 404);
        }
    }
}
