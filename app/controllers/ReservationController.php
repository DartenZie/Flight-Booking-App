<?php

namespace App\controllers;

use App\core\Controller;
use App\Exceptions\ValidationException;
use App\models\Airport;
use App\models\Flight;
use App\models\Plane;
use App\models\Reservation;
use App\utils\InputValidator;
use App\utils\MapperUtils;

class ReservationController extends Controller {
    /**
     * @var Reservation Instance of the Reservation model for data operations.
     */
    private Reservation $reservationModel;
    /**
     * @var Flight Instance of the Flight model for data operations.
     */
    private Flight $flightModel;
    /**
     * @var Airport Instance of the Airport model for data operations.
     */
    private Airport $airportModel;
    /**
     * @var Plane Instance of the Plane model for data operations.
     */
    private Plane $planeModel;

    /**
     * Initializes controller and its dependencies.
     */
    public function __construct() {
        parent::__construct();
        $this->reservationModel = new Reservation($this->db);
        $this->flightModel = new Flight($this->db);
        $this->airportModel = new Airport($this->db);
        $this->planeModel = new Plane($this->db);
    }

    public function index(): void {
        $this->handleRequest([
            'GET' => fn() => $this->getReservationById(),
            'POST' => fn() => $this->createReservation(),
            'DELETE' => fn() => $this->deleteReservation(),
        ]);
    }

    public function user(): void {
        $this->authenticateJWTToken();

        $this->handleRequest([
            'GET' => fn() => $this->getReservationsByUser(),
        ]);
    }

    /**
     * Fetches a reservation by ID.
     * Validates input and retrieves the reservation details including related entities.
     *
     * @throws ValidationException If validation fails or reservation is not found.
     */
    private function getReservationById(): void {
        $this->authenticateJWTToken();

        InputValidator::required($_GET, ['id']);

        $reservationId = InputValidator::sanitizeInt($_GET['id']);
        $reservation = $this->reservationModel->getReservationById($reservationId);

        if (!$reservation) {
            throw new ValidationException('Reservation not found.', 404);
        }

        // Only admin or owner user can access their own reservations
        if ($this->userData['id'] !== $reservation['user_id'] && $this->userData['permission_level'] < 3) {
            throw new ValidationException('Unauthorized.', 401);
        }

        $reservationDetails = MapperUtils::mapReservation($reservation, $this->flightModel, $this->planeModel, $this->airportModel);
        $this->jsonResponse($reservationDetails);
    }

    /**
     * Creates a new reservation.
     * Validates input, sanitizes data, and calls the model to save the reservation.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function createReservation(): void {
        $this->authenticateJWTToken();

        $data = $this->parseRequestBody();

        InputValidator::required($data, ['seat', 'flightId']);
        $flightId = InputValidator::sanitizeInt($data['flightId']);
        $createData = [
            'seat' => InputValidator::sanitizeString($data['seat']),
            'class' => $this->getClassFromSeat($data['seat'], $flightId),
            'user_id' => $this->userData['id'],
            'flight_id' => InputValidator::sanitizeInt($data['flightId']),
        ];

        // TODO check if seat is not taken
        // TODO check if the seat is correct

        $this->reservationModel->createReservation($createData);
        $this->jsonResponse(['message' => 'Reservation created successfully.'], 201);
    }

    /**
     * Deletes an existing reservation.
     * Validates input, sanitizes data, and calls the model to delete the reservation.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function deleteReservation(): void {
        $this->authenticateJWTToken();

        $data = $this->parseRequestBody();

        InputValidator::required($data, ['id']);
        $reservationId = InputValidator::sanitizeInt($data['id']);
        $reservation = $this->reservationModel->getReservationById($reservationId);

        // Only user or admin can remove their own reservations
        if ($this->userData['id'] !== $reservation['user_id'] && $this->userData['permission_level'] < 3) {
            throw new ValidationException('Unauthorized.', 401);
        }

        $this->reservationModel->deleteReservation($reservationId);
        $this->jsonResponse(['message' => 'Reservation deleted successfully.']);
    }

    /**
     * Fetches a reservation by user.
     * Supports pagination and retrieves reservations belonging to specific user.
     *
     * @throws ValidationException If validation or sanitization fails.
     */
    private function getReservationsByUser(): void {
        InputValidator::required($_GET, ['id']);

        $userId = InputValidator::sanitizeInt($_GET['id']);

        // Only user or admin can access their own reservations
        if ($this->userData['id'] !== $userId && $this->userData['permission_level'] < 3) {
            throw new ValidationException('Unauthorized.', 401);
        }

        $page = InputValidator::sanitizeInt($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $reservations = $this->reservationModel->getAllReservationsByUser($userId, $limit, $offset);
        $totalReservations = $this->reservationModel->getReservationsByUserCount($userId);

        $reservations = array_map(fn ($reservation) => MapperUtils::mapReservation($reservation, $this->flightModel, $this->planeModel, $this->airportModel), $reservations);

        $this->jsonResponse([
            'reservations' => $reservations,
            'total' => $totalReservations,
            'page' => $page,
            'totalPages' => ceil($totalReservations / $limit),
        ]);
    }


    /**
     * Determines the class type (e.g., economy, business) of a specific seat
     * on a flight based on seat configuration and flight details.
     *
     * @param string $seat The seat identifier in string format (e.g., "12A").
     * @param int $flightId The ID of the flight for which the seat class is to be determined.
     * @return string The name of the class associated with the given seat.
     *
     * @throws ValidationException If sanitization fails.
     */
    private function getClassFromSeat(string $seat, int $flightId): string {
        $seatRow = InputValidator::sanitizeInt(substr($seat, 0, strlen($seat) - 1));
        $flight = $this->flightModel->getFlightById($flightId);
        $plane = $this->planeModel->getPlaneById($flight['plane_id']);

        $regex = '/\[(\w+)\s+(\d+)\s+([\dx]+)]/';
        preg_match_all($regex, $plane['configuration'], $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $className = $match[1];
            $rows = $match[2];

            if ($seatRow <= $rows) {
                return $className;
            }
            $seatRow = $seatRow - $rows;
        }
        return '';
    }
}
