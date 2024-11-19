<script setup lang="ts">
import FormControl from "@/components/FormControl.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {useAuthStore} from "@/store/auth.store";
import {useRouter} from "vue-router";
import {ref} from "vue";

const auth = useAuthStore();
const router = useRouter();

const firstName = ref('');
const lastName = ref('');
const email = ref('');
const password = ref('');
const passwordConfirm = ref('');

async function handleSubmit(): void {
    if (password.value !== passwordConfirm.value) {
        return;
    }

    const success = await auth.register({
        firstName: firstName.value,
        lastName: lastName.value,
        email: email.value,
        password: password.value
    });

    if (success) {
        await auth.login(email.value, password.value);
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
                    <div class="mb-6 flex justify-between gap-x-6">
                        <div class="w-full">
                            <form-control id="firstName" v-model="firstName" label="First name" type="text" placeholder="John" />
                        </div>
                        <div class="w-full">
                            <form-control id="lastName" v-model="lastName" label="Last name" type="text" placeholder="Doe" />
                        </div>
                    </div>

                    <div class="mb-6">
                        <form-control id="login" v-model="email" label="E-mail" type="email" placeholder="johndoe@gmail.com" />
                    </div>

                    <div class="mb-6">
                        <form-control id="password" v-model="password" label="Password" type="password" />
                    </div>

                    <div class="mb-8">
                        <form-control id="confirmPassword" v-model="passwordConfirm" label="Confirm password" type="password" />
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
