import {computed} from 'vue';
import {defineStore} from 'pinia';
import {useFetch, useStorage} from '@vueuse/core';

const API_URL = 'http://localhost:8080';

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

    return { accessToken, isLoggedIn, login, logout, register };
});
