import {computed, ref, watch} from 'vue';
import {defineStore} from 'pinia';
import {useFetch, useStorage} from '@vueuse/core';
import {useAuthenticatedFetch} from "../utils/authenticated-fetch";

const API_URL = process.env.VITE_API_URL;

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
    const accessToken = useStorage('access_token', '', localStorage);
    const isLoggedIn = computed(() => !!accessToken.value);
    const user = ref<User>(null);

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

    async function fetchUser(): Promise<void> {
        try {
            const { data, statusCode } = await useAuthenticatedFetch(`${API_URL}/user`).get().json();

            if (statusCode.value !== 200) {
                return;
            }

            user.value = {
                id: data.value.id,
                email: data.value.email,
                firstName: data.value.firstName,
                lastName: data.value.lastName,
                nationality: data.value.nationality,
                sex: data.value.sex,
                dateOfBirth: data.value.dateOfBirth,
                phone: data.value.phone,
                permissionLevel: data.value.permissionLevel
            };
        } catch (error) {
            console.error('Failed to fetch user data:', error);
        }
    }

    watch(
        () => accessToken.value,
        () => {
            // noinspection JSIgnoredPromiseFromCall
            fetchUser();
        },
        { immediate: true },
    );

    function invalidateCache(): void {
        // noinspection JSIgnoredPromiseFromCall
        fetchUser();
    }

    return { accessToken, isLoggedIn, user, login, logout, register, invalidateCache};
});
