export interface UserModel {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
    nationality: string;
    dateOfBirth: string;
    phone: string;
    sex: string;
}

export interface UserResponse {
    id: number;
    firstName: string;
    lastName: string;
    email: string;
    role: string;
}

export interface UsersResponse {
    users: UserResponse[],
    total: number;
    page: number;
    totalPages: number;
}
