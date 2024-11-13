export interface SeatingModel {
    cabins: CabinModel[];
    takenSeats: number[];
}

export interface CabinModel {
    id: number;
    class: string;
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
