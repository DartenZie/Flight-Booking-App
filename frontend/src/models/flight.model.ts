import {Plane} from "./plane.model";

export interface FlightPlaneResponse {
    id: number;
    name: string;
    configuration: string;
    airline: string;
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

export class Flight {
    id: number;
    prices: Map<string, number>;
    departureTime: Date;
    arrivalTime: Date;
    plane: Plane;
    departureAirport: FlightAirportResponse;
    arrivalAirport: FlightAirportResponse;

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
        return flight;
    }

    private static parseFlightPrices(pricesString: string): Map<string, number> {
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
    airline: { name: string; logo: string };
    departure: { name: string; time: Date };
    arrival: { name: string };
    duration: number; // Duration in minutes
    price: number;
    type: 'oneWay' | 'outbound' | 'return' | 'selected';

    constructor(
        airline: { name: string; logo: string },
        departure: { name: string; time: Date },
        arrival: { name: string },
        duration: number,
        price: number,
        type: 'oneWay' | 'outbound' | 'return' | 'selected' = 'oneWay'
    ) {
        this.airline = airline;
        this.departure = departure;
        this.arrival = arrival;
        this.duration = duration;
        this.price = price;
        this.type = type;
    }

    getDepartureTime(): string {
        return this.formatTime(this.departure.time);
    }

    getArrivalTime(): string {
        const arrivalTime = new Date(
            this.departure.time.getTime() + this.duration * 60000
        );
        return this.formatTime(arrivalTime);
    }

    getFormattedDuration(): string {
        const hours = Math.floor(this.duration / 60);
        const minutes = this.duration % 60;
        return `${hours}h ${minutes}m`;
    }

    private formatTime(date: Date): string {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }
}

export type FlightResultType = Pick<FlightResult, keyof FlightResult>;


