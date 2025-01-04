<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\Airline;
use App\utils\InputValidator;
use App\utils\MapperUtils;
use Exception;
use RuntimeException;

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
        $this->airlineModel = new Airline($this->db);
    }

    /**
     * Main endpoint handler for airports.
     * Routes requests to appropriate methods based on HTTP request method.
     */
    public function index(): void {
        $this->authenticateJWTToken('flightManager');

        $this->handleRequest([
            'GET' => fn() => $this->getAirlineById(),
            'POST' => fn() => $this->createAirline(),
            'PUT' => fn() => $this->updateAirline(),
        ]);
    }

    /**
     * Endpoint for fetching all airlines.
     */
    public function list(): void {
        $this->authenticateJWTToken('flightManager');

        $this->handleRequest([
            'GET' => fn() => $this->getAllAirlines()
        ]);
    }

    /**
     * Endpoint for getting and uploading airline logo.
     */
    public function logo(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getLogo(),
            'POST' => fn() => $this->uploadLogo()
        ]);
    }

    /**
     * Fetches an airline by ID.
     * Validates input and retrieves the airline details.
     *
     * @throws ValidationException If validation fails or airline is not found.
     */
    private function getAirlineById(): void {
        InputValidator::required($_GET, ['id']);

        $airlineId = InputValidator::sanitizeInt($_GET['id']);
        $airline = $this->airlineModel->getAirlineById($airlineId);

        if (!$airline) {
            throw new ValidationException('Airline not found.', 404);
        }

        $airlineDetails = MapperUtils::mapAirline($airline);
        $this->jsonResponse($airlineDetails);
    }

    /**
     * Fetches all airlines.
     * Supports pagination and retrieves all airlines.
     */
    private function getAllAirlines(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $airlines = $this->airlineModel->getAllAirlines($limit, $offset) ?? [];
        $totalAirlines = $this->airlineModel->getAirlineCount();

        $airlines = array_map(fn ($airline) => MapperUtils::mapAirline($airline), $airlines);

        $this->jsonResponse([
            'airlines' => $airlines,
            'total' => $totalAirlines,
            'page' => $page,
            'totalPages' => ceil($totalAirlines / $limit)
        ]);
    }

    /**
     * Creates a new airline.
     * Validates input, sanitizes data, and calls the model to save the airline.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function createAirline(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['name']);
        $createData = [
            'name' => InputValidator::sanitizeString($data['name'])
        ];

        $this->airlineModel->createAirline($createData);
        $this->jsonResponse(['message' => 'Airline created successfully.'], 201);
    }

    /**
     * Updates an existing airline.
     * Validates input, dynamically builds the update array, and calls the model to save changes.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function updateAirline(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['id']);

        $id = InputValidator::sanitizeInt($data['id']);
        $airline = $this->airlineModel->getAirlineById($id);
        if (!$airline) {
            throw new ValidationException('Airline not found.', 404);
        }

        $updateData = [];
        $fieldMappings = [
            'name' => 'name'
        ];

        foreach ($fieldMappings as $inputField => $dbField) {
            if (isset($data[$inputField])) {
                $sanitizer = match ($inputField) {
                    'name' => InputValidator::sanitizeString($data[$inputField]),
                    default => null
                };
                if ($sanitizer) {
                    $updateData[$dbField] = InputValidator::$sanitizer($data[$inputField]);
                }
            }
        }

        $this->airlineModel->updateAirline($id, $updateData);

        $this->jsonResponse(['message' => 'Airline updated successfully.']);
    }

    /**
     * Retrieves the logo of a specific airline by its ID.
     *
     * @throws ValidationException If validation fails or airline is not found.
     */
    private function getLogo(): void {
        InputValidator::required($_GET, ['airlineId']);

        $airlineId = InputValidator::sanitizeInt($_GET['airlineId']);
        $airline = $this->airlineModel->getAirlineById($airlineId);

        if (!$airline || empty($airline['logo_path'])) {
            throw new ValidationException('Logo not found.', 404);
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
     * @throws ValidationException If validation fails or airline is not found
     */
    private function uploadLogo(): void {
        $this->authenticateJWTToken('flightManager');

        InputValidator::required($_GET, ['airlineId']);
        $airlineId = InputValidator::sanitizeInt($_GET['airlineId']);
        $airline = $this->airlineModel->getAirlineById($airlineId);
        if (!$airline) {
            throw new ValidationException('Airline not found.', 404);
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->jsonResponse(['message' => 'Invalid file upload.'], 400);
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

        $this->jsonResponse(['message' => 'Logo uploaded successfully.']);
    }
}
