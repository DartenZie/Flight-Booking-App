export interface SeatingModel {
    cabins: CabinModel[];
    takenSeats: string[];
}

export interface CabinModel {
    id: number;
    className: string;
    rows: number;
    moreLegRoom?: number[];
    isles: IsleModel[];
}

export interface IsleModel {
    id: number;
    seats: Seat[];
}

export interface Seat {
    id: number;
    colCode: string;
    legRoom: number;
}

export interface PlaneResponse {
    id: number;
    name: string;
    configuration: string;
    airlineName: string;
    airlineId: number;
}

export interface PlanesResponse {
    planes: PlaneResponse[];
    total: number;
    page: number;
    totalPages: number;
}

export class Plane {
    id: number;
    name: string;
    seatingConfiguration: SeatingModel;
    airline: string;
    airlineId: number;

    constructor(id: number, name: string) {
        this.id = id;
        this.name = name;
    }

    calculateTotalSeats(): number {
        let total = 0;
        for (const cabin of this.seatingConfiguration.cabins) {
            total += this.calculateSeatsPerCabin(cabin);
        }
        return total;
    }

    calculateSeatsPerCabin(cabin: CabinModel): number {
        let total = 0;
        const seatsPerRow = cabin.isles.reduce((total, isle) => total + isle.seats.length, 0);
        total += seatsPerRow * cabin.rows;
        return total;
    }

    static parsePlane(planeResponse: PlaneResponse): Plane {
        const plane = new Plane(planeResponse.id, planeResponse.name);
        plane.airline = planeResponse.airlineName;
        plane.airlineId = planeResponse.airlineId;
        plane.seatingConfiguration = this.parsePlaneSeatingConfiguration(planeResponse.configuration);
        return plane;
    }

    static parsePlaneSeatingConfiguration(input: string): SeatingModel {
        const SEAT_LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let currentCabinId = 0;
        let currentSeatId = 0;
        let currentIsleId = 0;

        const cabins: CabinModel[] = [];

        // Regular expression to match cabin configurations
        const cabinRegex = /\[(\w+)\s+(\d+)\s+([\dx]+)]/g;
        let match;

        while ((match = cabinRegex.exec(input)) !== null) {
            const [_, className, rows, seatConfig] = match;
            const seatGroups = seatConfig.split('x').map(Number);
            const isles: IsleModel[] = [];

            let currentColIndex = 0;

            seatGroups.forEach((seatsInGroup) => {
                const seats: Seat[] = [];

                for (let i = 0; i < seatsInGroup; i++) {
                    seats.push({
                        id: currentSeatId++,
                        colCode: SEAT_LETTERS[currentColIndex++],
                        legRoom: 0
                    });
                }

                isles.push({
                    id: currentIsleId++,
                    seats
                });
            });

            cabins.push({
                id: currentCabinId++,
                className,
                rows: parseInt(rows),
                moreLegRoom: [],
                isles
            });
        }

        return {
            cabins,
            takenSeats: []
        };
    }

    static validateSeatingConfig(config: string): boolean {
        const regex = /^\[\w+\s+\d+\s+(?:\d+x)*\d+](?:\s+\[\w+\s+\d+\s+(?:\d+x)*\d+])*$/;
        return regex.test(config);
    }
}
