import {Plane} from "./plane.model";
import {Airline} from "../store/airline.store";

export interface FlightPlaneResponse {
    id: number;
    name: string;
    configuration: string;
    airlineName: string;
    airlineId: number;
}

export interface FlightAirportResponse {
    id: number;
    name: string;
    city: string;
    country: string;
    iata: string;
    timezone: string;
}

export interface FlightResponse {
    id: number;
    price: string;
    departureTime: Date;
    arrivalTime: Date;
    plane: FlightPlaneResponse;
    departureAirport: FlightAirportResponse;
    arrivalAirport: FlightAirportResponse;
    cancelled: boolean;
}

export interface FlightsResponse {
    flights: FlightResponse[];
    total: number;
    page: number;
    totalPages: number;
}

export interface CreateFlightRequest {
    price: string;
    departureTime: string;
    arrivalTime: string;
    planeId: number;
    departureAirportId: number;
    arrivalAirportId: number;
}

export interface UpdateFlightRequest extends Partial<CreateFlightRequest> {
    id: number;
    cancelled?: boolean;
}

export class Flight {
    id: number;
    prices: Map<string, number>;
    departureTime: Date;
    arrivalTime: Date;
    plane: Plane;
    departureAirport: FlightAirportResponse;
    arrivalAirport: FlightAirportResponse;
    cancelled: boolean;

    constructor(id: number) {
        this.id = id;
    }

    static parseFlight(flightResponse: FlightResponse): Flight {
        const flight = new Flight(flightResponse.id);
        flight.prices = this.parseFlightPrices(flightResponse.price);
        flight.departureTime = new Date(flightResponse.departureTime);
        flight.arrivalTime = new Date(flightResponse.arrivalTime);
        flight.plane = Plane.parsePlane(flightResponse.plane);
        flight.departureAirport = flightResponse.departureAirport;
        flight.arrivalAirport = flightResponse.arrivalAirport;
        flight.cancelled = flightResponse.cancelled;
        return flight;
    }

    static parseFlightPrices(pricesString: string): Map<string, number> {
        const flightPrices: { className: string, price: number }[] = [];
        const regex = /\[(.*?) (.*?)]/g;

        let match;
        while ((match = regex.exec(pricesString)) !== null) {
            const className = match[1];
            const price = parseFloat(match[2]);

            if (!isNaN(price)) {
                flightPrices.push({ className, price });
            }
        }

        return new Map(flightPrices.map(fp => [fp.className, fp.price]));
    }
}


export class FlightResult {
    id: number;
    airline: { name: string; logo: string };
    departure: { name: string; time: Date };
    arrival: { name: string; time: Date };
    price: string;
    type: 'oneWay' | 'outbound' | 'return' | 'selected';

    constructor(
        id: number,
        airline: { name: string; logo: string },
        departure: { name: string; time: Date },
        arrival: { name: string; time: Date },
        price: string,
        type: 'oneWay' | 'outbound' | 'return' | 'selected' = 'oneWay'
    ) {
        this.id = id;
        this.airline = airline;
        this.departure = departure;
        this.arrival = arrival;
        this.price = price;
        this.type = type;
    }

    getDepartureTime(): string {
        return FlightResult.formatTime(this.departure.time);
    }

    getArrivalTime(): string {
        return FlightResult.formatTime(this.arrival.time);
    }

    getFormattedDuration(): string {
        const duration = (this.arrival.time.valueOf() - this.departure.time.valueOf()) / 60_000;
        return FlightResult.formatDuration(duration);
    }

    static formatDuration(duration: number): string {
        const hours = Math.floor(duration / 60);
        const minutes = duration % 60;
        return `${hours}h ${minutes}m`;
    }

    static formatTime(date: Date): string {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }
}

export type FlightResultType = Pick<FlightResult, keyof FlightResult>;


