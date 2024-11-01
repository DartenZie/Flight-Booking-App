export class Flight {
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

export type FlightType = Pick<Flight, keyof Flight>;


