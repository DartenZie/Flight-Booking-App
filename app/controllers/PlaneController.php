<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\Airline;
use App\models\Plane;
use App\utils\InputValidator;
use App\utils\MapperUtils;
use PDOException;

class PlaneController extends Controller {
    /**
     * @var Plane Instance of the Plane model for data operations.
     */
    private Plane $planeModel;
    /**
     * @var Airline Instance of the Airline model for data operations.
     */
    private Airline $airlineModel;

    /**
     * Initializes controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->planeModel = new Plane($this->db);
        $this->airlineModel = new Airline($this->db);
    }

    public function index(): void {
        $this->authenticateJWTToken('flightManager');

        $this->handleRequest([
            'GET' => fn() => $this->getPlaneById(),
            'POST' => fn() => $this->createPlane(),
            'DELETE' => fn() => $this->deletePlane()
        ]);
    }

    /**
     * Fetches a plane by ID.
     * Validate input and retrieves the plane details.
     *
     * @throws ValidationException If validation fails or plane is not found.
     */
    private function getPlaneById(): void {
        InputValidator::required($_GET, ['id']);

        $planeId = InputValidator::sanitizeInt($_GET['id']);
        $plane = $this->planeModel->getPlaneById($planeId);

        if (!$plane) {
            throw new ValidationException('Plane not found.', 404);
        }

        $planeDetails = MapperUtils::mapPlane($plane);
        $this->jsonResponse($planeDetails);
    }

    /**
     * Creates a new plane for specific airline.
     * Validates input, sanitizes data, and calls the model to save the plane.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function createPlane(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['name', 'configuration', 'airlineId']);

        $airlineId = InputValidator::sanitizeInt($data['airlineId']);
        $airline = $this->airlineModel->getAirlineById($airlineId);

        if (!$airline) {
            throw new ValidationException('Airline not found.', 404);
        }

        $createData = [
            'name' => InputValidator::sanitizeString($data['name']),
            'configuration' => InputValidator::sanitizeString($data['configuration']),
            'airlineId' => $airlineId,
        ];

        $this->planeModel->createPlane($createData);
        $this->jsonResponse(['message' => 'Plane created successfully.'], 201);
    }

    /**
     * Deletes an existing plane.
     * Validates input, sanitizes data, and calls the model to delete the plane.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function deletePlane(): void {
        $data = $this->parseRequestBody();

        InputValidator::required($data, ['id']);

        $planeId = InputValidator::sanitizeInt($data['id']);
        $plane = $this->planeModel->getPlaneById($planeId);

        if (!$plane) {
            throw new ValidationException('Plane not found.', 404);
        }

        try {
            $this->planeModel->deletePlane($planeId);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new ValidationException('Plane is associated with one or more flights.', 400);
            }
            throw $e;
        }
        $this->jsonResponse(['message' => 'Plane deleted successfully.']);
    }
}
