<script setup lang="ts">
import FormControl from "@/components/FormControl.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <div class="h-screen flex items-center justify-center">
        <div class="w-full max-w-screen-lg grid grid-cols-1 lg:grid-cols-7 gap-x-36">
            <div class="lg:col-span-3 h-full content-center">
                <h1 class="font-medium text-3xl mb-10">Get on Board!</h1>

                <form>
                    <div class="mb-6 flex justify-between gap-x-6">
                        <div class="w-full"><form-control id="firstName" label="First name" type="text" placeholder="John" /></div>
                        <div class="w-full"><form-control id="lastName" label="Last name" type="text" placeholder="Doe" /></div>
                    </div>

                    <div class="mb-6">
                        <form-control id="login" label="E-mail" type="email" placeholder="johndoe@gmail.com" />
                    </div>

                    <div class="mb-6">
                        <form-control id="password" label="Password" type="password" />
                    </div>

                    <div class="mb-8">
                        <form-control id="confirmPassword" label="Confirm password" type="password" />
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
