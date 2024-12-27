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
            case 'PUT': $this->updateAirline(); break;
            default:
                http_response_code(405);
                header('ALLOW: GET, POST');
                exit();
        }
    }

    public function logo(): void {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $this->getLogo(); break;
            case 'POST':
                $this->authenticateJWTToken('flightManager');
                $this->uploadLogo();
                break;
            default:
                http_response_code(405);
                header('ALLOW: GET, POST, PUT');
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

        if (!isset($data)) {
            $this->jsonResponse(['message' => 'Empty body provided.'], 400);
        }

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

        $airlines = array_map(fn($airline) => [
            'id' => $airline['id'],
            'name' => $airline['name'],
        ], $airlines);

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

    /**
     * Updates the details of a specific airline.
     */
    private function updateAirline(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data)) {
            $this->jsonResponse(['message' => 'Empty body provided.'], 400);
        }

        if (empty($data['id']) || !is_numeric($data['id'])) {
            $this->jsonResponse(['message' => 'Invalid or missing airline ID.'], 400);
        }

        $airlineId = $data['id'];
        $airline = $this->airlineModel->getAirlineById($airlineId);

        if (!$airline) {
            $this->jsonResponse(['message' => 'Airline not found.'], 401);
        }

        $fieldsToUpdate = [];
        if (!empty($data['name'])) {
            $fieldsToUpdate['name'] = $data['name'];
        }

        if (empty($fieldsToUpdate)) {
            $this->jsonResponse(['message' => 'No valid fields provided to update.'], 400);
        }

        try {
            $this->airlineModel->updateAirline($airlineId, $fieldsToUpdate);
            $this->jsonResponse(['message' => 'Airline updated successfully.']);
        } catch (Exception $e) {
            $this->jsonResponse(['message' => 'Failed to update airline.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieves the logo of a specific airline by its ID.
     */
    private function getLogo(): void {
        $airlineId = $_GET['airlineId'];

        if (empty($airlineId) || !is_numeric($airlineId)) {
            $this->jsonResponse(['message' => 'Invalid airline Id.'], 400);
        }

        $airline = $this->airlineModel->getAirlineById($airlineId);

        if (!$airline || empty($airline['logo_path'])) {
            $this->jsonResponse(['message' => 'Logo not found.'], 404);
        }

        $logoPath = __DIR__ . '/../' . $airline['logo_path'];

        if (!file_exists($logoPath)) {
            $this->jsonResponse(['message' => 'Logo file not found on server.'], 404);
        }

        header('Content-Type: ' . mime_content_type($logoPath));
        readfile($logoPath);
        exit();
    }

    /**
     * Handles the upload of an airline logo.
     *
     * @throws RuntimeException If the file upload fails or is invalid.
     */
    private function uploadLogo(): void {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->jsonResponse(['message' => 'Invalid file upload.'], 400);
        }

        $airlineId = $_GET['airlineId'] ?? '';
        if (empty($airlineId) || !$this->airlineModel->getAirlineById($airlineId)) {
            $this->jsonResponse(['message' => 'Airline not found.'], 404);
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['file']['type'], $allowedTypes)) {
            $this->jsonResponse(['message' => 'Invalid file type. Only JPG and PNG are allowed.']);
        }

        $uploadDir = __DIR__ . '/../uploads/logos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('logo_') . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            $this->jsonResponse(['message' => 'Failed to save the uploaded file.'], 500);
        }

        $relativePath = 'uploads/logos/' . $fileName;
        $this->airlineModel->updateAirline($airlineId, ['logo_path' => $relativePath]);

        $this->jsonResponse(['message' => 'Logo uploaded successfully.', 'path' => $relativePath]);
    }
}
