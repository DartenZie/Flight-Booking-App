
export interface DecodedToken {
    exp: number;
}

export class JwtUtils {
    /**
     * Decodes a JWT token and returns the payload.
     *
     * @param token The JWT token to decode.
     * @returns The decoded token payload.
     * @throws An error if the token is invalid or expired.
     */
    static decodeToken(token: string): DecodedToken | null {
        try {
            const parts = token.split('.');
            if (parts.length !== 3) {
                console.error('Invalid token format.');
                return null;
            }

            const payload = JwtUtils.base64UrlDecode(parts[1]);
            return JSON.parse(payload);
        } catch (error) {
            console.error('Failed to decode token:', error);
            return null;
        }
    }

    /**
     * Decodes the payload of a JWT token.
     *
     * @param str The Base64 URL encoded string to decode.
     * @returns The decoded string.
     */
    private static base64UrlDecode(str: string): string {
        const base64 = str.replace(/-/g, '+').replace(/_/g, '/');
        return decodeURIComponent(atob(base64).split('').map(char =>
            '%' + ('00' + char.charCodeAt(0).toString(16)).slice(-2)
        ).join(''));
    }
}
