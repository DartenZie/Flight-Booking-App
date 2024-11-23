import {helpers} from '@vuelidate/validators';
import {computed} from "vue";

/**
 * Checks if a given string contains a specific substring.
 *
 * @param paramRef The substring to search for.
 */
export const contains = (paramRef) => (value) => {
    const param = computed(() => paramRef.value);
    return !helpers.req(value) || value.includes(param.value);
};

/**
 * Checks if a given string does not contain a specific substring.
 *
 * @param paramRef The substring to search for.
 */
export const doesNotContain = (paramRef) => (value) => {
    const param = computed(() => paramRef.value);
    return !helpers.req(value) || !helpers.req(param.value) || !value.toLowerCase().includes(param.value.toLowerCase());
};

/**
 * Checks if a given string contains a number or a special character.
 */
export const containsNumberOrSpecialChar = helpers.regex(/[0-9]|[!@#$%^&*(),.?":{}|<>]/);
