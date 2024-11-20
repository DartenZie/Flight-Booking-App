import {useFetch, UseFetchOptions, UseFetchReturn} from '@vueuse/core';
import {useAuthStore} from '../store/auth.store';

const BASE_URL = 'http://localhost:8080';

/**
 * Custom hook for making authenticated fetch requests with VueUse's useFetch.
 *
 * @param url Endpoint url.
 * @param options Request options.
 */
export function useAuthenticatedFetch<T>(url: string, options: UseFetchOptions = {}):  UseFetchReturn<T> & PromiseLike<UseFetchReturn<T>> {
    const auth = useAuthStore();

    // Helper function to append the Authorization token to headers if available.
    const appendToken = (headers: Headers) => {
        const token = auth.accessToken;
        if (token) {
            headers.set('Authorization', `Bearer ${token}`);
        }
    };

    // Handles refreshing the access token by making a request to the server
    // and updating the stored token in the auth store.
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

    // Custom fetch function with retry logic to handle expired tokens.
    const fetchWithRetry = async (input: RequestInfo, init?: RequestInit): Promise<void> => {
        try {
            const headers = new Headers(init?.headers);
            appendToken(headers);

            const response = await fetch(input, {...init, headers});

            if (!response.ok) {
                const data = await response.text();
                if (data === 'token_expired') {
                    // If the token is expired, refresh it and retry the request
                    // Refresh token is stored in HTTP-only cookie
                    await refreshAccessToken();
                    appendToken(headers);
                    return fetch(input, {...init, headers});
                }
            }

            return response;
        } catch (error) {
            console.error('Fetch failed:', error);
            auth.logout();
            throw error;
        }
    };

    const fetchOptions: UseFetchOptions = {
        ...options,
        fetch: fetchWithRetry,
    };

    return useFetch<T>(url, fetchOptions);
}
