export interface AirportModel {
    city: string;
    country: string;
    iata: string;
    id: number;
    name: string;
    timezone: string;
}

export interface AirportSearchResponse {
    airports: AirportModel[];
    page: number;
    total: number;
    totalPages: number;
}
