<script setup lang="ts">
import {useRoute} from "vue-router";
import {computed} from "vue";

const route = useRoute();

const showRegister = computed(() => ['/sign-in', '/reset-password'].includes(route.path));
const showSignIn = computed(() => ['/sign-up'].includes(route.path));
</script>

<template>
    <nav class="absolute top-0 w-full">
        <div class="container mx-auto p-4 md:px-8 xl:px-0 flex justify-between items-center">
            <router-link to="/" class="block">
                <span class="text-xl">
                    Sky<span class="font-semibold">Trip</span>
                </span>
            </router-link>

            <div class="flex gap-x-8">
                <router-link v-if="showRegister" to="/sign-up" class="btn-text h-10">Register</router-link>
                <router-link v-if="showSignIn" to="/sign-in" class="btn-text h-10">Sign In</router-link>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 md:px-8 xl:px-0">
        <slot />
    </div>

    <div class="absolute bottom-0 w-full">
        <div class="container mx-auto p-4 md:px-8 xl:px-0 flex justify-center items-center gap-x-2 md:gap-x-4 lg:gap-x-6">
            <div class="text-slate-700">&copy; Miroslav Pašek 2024</div>
            <div class="text-slate-400">|</div>
            <div class="text-slate-700">All rights reserved</div>
        </div>
    </div>
</template>
