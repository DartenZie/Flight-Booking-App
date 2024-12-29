import {computed, ref} from 'vue';
import {defineStore} from 'pinia';
import {useFetch, useStorage} from '@vueuse/core';
import {useAuthenticatedFetch} from "../utils/authenticated-fetch";

const API_URL = 'http://localhost:8080';

export interface User {
    id: number;
    email: string;
    firstName: string;
    lastName: string;
    nationality: string;
    sex: string;
    dateOfBirth: string;
    phone: string;
    permissionLevel: number;
}

interface RegisterModel {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
}

interface TokenResponse {
    access_token: string;
}

interface RegisterResponse {
    error: boolean;
}

export const useAuthStore = defineStore('auth', () => {
    // Access token used to authorize requests
    const accessToken = useStorage('access_token', '', localStorage);

    // Cache for user data
    const cachedUser = ref<User>(null);

    // Flag if user is logged in
    const isLoggedIn = computed(() => !!accessToken.value);

    async function login(email: string, password: string): Promise<void> {
        try {
            const { data } = await useFetch<TokenResponse>(`${API_URL}/login`, {credentials: 'include'})
                .post({ email, password }).json();

            if (data.value?.access_token) {
                accessToken.value = data.value.access_token;
            }
        } catch {
            throw new Error('login_failed');
        }
    }

    function logout(): void {
        accessToken.value = '';
    }

    async function register(user: RegisterModel): Promise<boolean> {
        try {
            const { data } = await useFetch<RegisterResponse>(`${API_URL}/register`)
                .post(user).json();
            return data.value?.error === false;
        } catch (error) {
            console.error('Register failed:', error);
            return false;
        }
    }

    async function user(): Promise<User | null> {
        if (!isLoggedIn.value) {
            return null;
        }

        if (cachedUser.value) {
            return cachedUser.value;
        }

        try {
            const { data } = await useAuthenticatedFetch(`${API_URL}/user`).get().json();

            cachedUser.value = {
                id: data.value.id,
                email: data.value.email,
                firstName: data.value.firstName,
                lastName: data.value.lastName,
                nationality: data.value.nationality,
                sex: data.value.sex,
                dateOfBirth: data.value.dateOfBirth,
                phone: data.value.phone,
                permissionLevel: data.value.permission_level
            };

            return cachedUser.value;
        } catch (error) {
            console.error('Failed to fetch user data:', error);
            return null;
        }
    }

    return { accessToken, isLoggedIn, login, logout, register, user };
});
