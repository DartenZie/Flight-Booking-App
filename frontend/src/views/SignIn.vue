<script setup lang="ts">
import FormControl from '@/components/FormControl.vue';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {faChevronRight} from '@fortawesome/free-solid-svg-icons';
import {reactive} from 'vue';
import {useAuthStore} from '@/store/auth.store';
import {useRouter} from 'vue-router';
import useVuelidate, {ValidationArgs} from "@vuelidate/core";
import {required, email} from "@vuelidate/validators";

const auth = useAuthStore();
const router = useRouter();

const state = reactive({
    email: '',
    password: '',
});

const rules: ValidationArgs = {
    email: { required, email },
    password: { required }
};

const v$ = useVuelidate(rules, state);

async function handleSubmit(): void {
    const isFormCorrect = await this.v$.$validate();
    if (!isFormCorrect) {
        return;
    }

    await auth.login(state.email, state.password);
    await router.push('/profile/dashboard');
}
</script>

<template>
    <div class="h-screen flex items-center justify-center">
        <div class="w-full max-w-screen-lg grid grid-cols-1 lg:grid-cols-7 gap-x-36">
            <div class="lg:col-span-3 h-full content-center">
                <h1 class="font-medium text-3xl mb-10">Welcome back!</h1>

                <form @submit.prevent="handleSubmit()">
                    <div class="mb-6">
                        <form-control id="login" v-model="state.email" label="E-mail" type="text" placeholder="johndoe@gmail.com" />
                        <p v-if="v$.email.$errors.some(v => v.$validator === 'required')" class="text-sm/6 text-red-600">Email is required.</p>
                        <p v-if="v$.email.$errors.some(v => v.$validator === 'email')" class="text-sm/6 text-red-600">Email is not valid.</p>
                    </div>

                    <div class="mb-8">
                        <form-control id="password" v-model="state.password" label="Password" type="password" />
                        <p v-if="v$.password.$error" class="text-sm/6 text-red-600">Password is required.</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <router-link class="btn-text h-8" to="/reset-password">Forgot password?</router-link>

                        <button class="btn-primary h-12" type="submit">
                            <span class="me-7">Sign In</span>
                            <font-awesome-icon :icon="faChevronRight" />
                        </button>
                    </div>
                </form>
            </div>
            <div class="hidden lg:block lg:col-span-4 h-full content-center">
                <img src="../assets/illustrations/login_illustration.svg" alt="Login illustration" class="w-full" />
            </div>
        </div>
    </div>
</template>
