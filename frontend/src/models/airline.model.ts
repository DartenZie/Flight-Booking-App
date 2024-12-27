export interface AirlineResponse {
    id: number;
    name: string;
}

export interface AirlinesResponse {
    airlines: AirlineResponse[];
    total: number;
    page: number;
    totalPages: number;
}
