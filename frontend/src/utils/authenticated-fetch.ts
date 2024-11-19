import {useFetch, UseFetchOptions, UseFetchReturn} from '@vueuse/core';
import {useAuthStore} from '../store/auth.store';

const BASE_URL = 'http://localhost:8080';

export function useAuthenticatedFetch<T>(url: string, options: UseFetchOptions = {}):  UseFetchReturn<T> & PromiseLike<UseFetchReturn<T>> {
    const auth = useAuthStore();

    const appendToken = (headers: Headers) => {
        const token = auth.accessToken;
        if (token) {
            headers.set('Authorization', `Bearer ${token}`);
        }
    };

    const refreshAccessToken = async (): Promise<void> => {
        const response = await fetch(`${BASE_URL}/refresh`, {
            method: 'POST',
            credentials: 'include',
        });

        if (!response.ok) {
            throw new Error('refresh_failed');
        }

        const data = await response.json();
        auth.accessToken = data.access_token;
    };

    const fetchWithRetry = async (input: RequestInfo, init?: RequestInit): Promise<void> => {
        try {
            const headers = new Headers(init?.headers);
            appendToken(headers);

            const response = await fetch(input, {...init, headers});

            if (!response.ok) {
                const data = await response.text();
                if (data === 'token_expired') {
                    await refreshAccessToken();
                    appendToken(headers);
                    return fetch(input, {...init, headers});
                }
            }

            return response;
        } catch (error) {
            console.error('Fetch failed:', error);
            throw error;
        }
    };

    const fetchOptions: UseFetchOptions = {
        ...options,
        fetch: fetchWithRetry,
    };

    return useFetch<T>(url, fetchOptions);
}
