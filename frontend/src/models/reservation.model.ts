import {Flight, FlightResponse} from "./flight.model";

export interface ReservationResponse {
    id: number;
    seat: string;
    class: string;
    flight: FlightResponse
}

export interface ReservationsResponse {
    reservations: ReservationResponse[],
    total: number;
    page: number;
    totalPages: number;
}

export class Reservation {
    id: number;
    seat: string;
    class: string;
    flight: Flight;

    constructor(id: number) {
        this.id = id;
    }

    static parseReservation(reservationResponse: ReservationResponse): Reservation {
        const reservation = new Reservation(reservationResponse.id);
        reservation.seat = reservationResponse.seat;
        reservation.class = reservationResponse.class;
        reservation.flight = Flight.parseFlight(reservationResponse.flight);
        return reservation;
    }
}
