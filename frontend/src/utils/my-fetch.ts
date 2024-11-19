import {createFetch} from '@vueuse/core';
import {useAuthStore} from '../store/auth.store';
import {JwtUtils} from "./jwt.utils";

// Might happen that token is valid when sending request, but is no longer valid when received on server.
// This solution would end in error in this case.

const auth = useAuthStore();

export const useAuthenticatedFetch = createFetch({
    baseUrl: 'http://localhost:8080',
    combination: 'overwrite',
    options: {
        async beforeFetch({ options, cancel }) {
            const accessToken = auth.accessToken;
            const accessTokenExp = JwtUtils.decodeToken(accessToken).exp;
            const currentTimestamp = Math.floor(Date.now() / 1000);

            if (accessTokenExp < currentTimestamp) {
                const refreshToken = auth.refreshToken;
                const refreshTokenExp = JwtUtils.decodeToken(refreshToken).exp;

                if (refreshTokenExp < refreshTokenExp) {
                    cancel();
                    auth.logout();
                    return { options };
                }

                try {
                    await auth.refresh();
                } catch {
                    cancel();
                    auth.logout();
                    return { options };
                }
            }

            const token = auth.accessToken;
            options.headers.Authorization = `Bearer ${token}`;

            return { options };
        }
    }
});
