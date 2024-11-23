<script setup lang="ts">
import FormControl from "@/components/FormControl.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faChevronRight, faCheckCircle} from "@fortawesome/free-solid-svg-icons";
import {useAuthStore} from "@/store/auth.store";
import {useRouter} from "vue-router";
import {computed, reactive} from "vue";
import useVuelidate, {ValidationArgs} from "@vuelidate/core";
import {required, email, minLength, sameAs} from "@vuelidate/validators";
import {containsNumberOrSpecialChar, doesNotContain} from "@/utils/validations";

const auth = useAuthStore();
const router = useRouter();

const state = reactive({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    passwordConfirm: ''
});

const rules: ValidationArgs = {
    firstName: { required },
    lastName: { required },
    email: { required, email },
    password: {
        required,
        noFirstName: doesNotContain(computed(() => state.firstName)),
        noLastName: doesNotContain(computed(() => state.lastName)),
        noEmail: doesNotContain(computed(() => state.email)),
        minLength: minLength(8),
        numOrSpecialChar: containsNumberOrSpecialChar,
        $autoDirty: true
    },
    passwordConfirm: { sameAs: sameAs(computed(() => state.password)) }
};

const v$ = useVuelidate(rules, state);

async function handleSubmit(): void {
    const isFormCorrect = await this.v$.$validate();
    if (!isFormCorrect) {
        return;
    }

    const success = await auth.register({
        firstName: state.firstName,
        lastName: state.lastName,
        email: state.email,
        password: state.password
    });

    if (success) {
        await auth.login(state.email, state.password);
        await router.push('/profile/dashboard');
    }
}
</script>

<template>
    <div class="h-screen flex items-center justify-center">
        <div class="w-full max-w-screen-lg grid grid-cols-1 lg:grid-cols-7 gap-x-36">
            <div class="lg:col-span-3 h-full content-center">
                <h1 class="font-medium text-3xl mb-10">Get on Board!</h1>

                <form @submit.prevent="handleSubmit()">
                    <div class="mb-6">
                        <div class="flex justify-between gap-x-6">
                            <div class="w-full">
                                <form-control id="firstName" v-model="state.firstName" label="First name" type="text" placeholder="John" />
                            </div>
                            <div class="w-full">
                                <form-control id="lastName" v-model="state.lastName" label="Last name" type="text" placeholder="Doe" />
                            </div>
                        </div>
                        <p v-if="v$.firstName.$error || v$.lastName.$error" class="text-sm/6 text-red-600">Full name is required.</p>
                    </div>

                    <div class="mb-6">
                        <form-control id="login" v-model="state.email" label="E-mail" type="text" placeholder="johndoe@gmail.com" />
                        <p v-if="v$.email.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Email is required.</p>
                        <p v-if="v$.email.$errors.some(v => v.$validator === 'email')" class="text-sm/6 text-red-600">Email is not valid.</p>
                    </div>

                    <div class="mb-6">
                        <form-control id="password" v-model="state.password" label="Password" type="password" />
                        <div class="space-y-1 mt-3">
                            <div class="flex gap-x-3 items-center" :class="[
                                !v$.password.$dirty ? 'text-gray-400' : '',
                                v$.password.$errors.some(v => ['required', 'noFirstName', 'noLastName', 'noEmail'].includes(v.$validator)) ? 'text-red-600' : 'text-green-600',
                            ]">
                                <font-awesome-icon :icon="faCheckCircle" class="text-sm/6" />
                                <p class="text-sm/6 font-light">Must not contain your name or email</p>
                            </div>
                            <div class="flex gap-x-3 items-center" :class="[
                                !v$.password.$dirty ? 'text-gray-400' : '',
                                v$.password.$errors.some(v => ['required', 'minLength'].includes(v.$validator)) ? 'text-red-600' : 'text-green-600',
                            ]">
                                <font-awesome-icon :icon="faCheckCircle" class="text-sm/6" />
                                <p class="text-sm/6 font-light">At least 8 characters</p>
                            </div>
                            <div class="flex gap-x-3 items-center"  :class="[
                                !v$.password.$dirty ? 'text-gray-400' : '',
                                v$.password.$errors.some(v => ['required', 'numOrSpecialChar'].includes(v.$validator)) ? 'text-red-600' : 'text-green-600',
                            ]">
                                <font-awesome-icon :icon="faCheckCircle" class="text-sm/6" />
                                <p class="text-sm/6 font-light">Contains a symbol or a number</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <form-control id="confirmPassword" v-model="state.passwordConfirm" label="Confirm password" type="password" />
                        <p v-if="v$.passwordConfirm.$error" class="text-sm/6 text-red-600">Passwords are not same.</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <router-link class="btn-text h-8" to="/register-airline">Join as Airline</router-link>
                        <button class="btn-primary h-12" type="submit">
                            <span class="me-7">Sign Up</span>
                            <font-awesome-icon :icon="faChevronRight" />
                        </button>
                    </div>
                </form>
            </div>
            <div class="lg:col-span-4 h-full content-center">
                <img src="../assets/illustrations/register_illustration.svg" alt="Register illustration" class="w-full" />
            </div>
        </div>
    </div>
</template>
