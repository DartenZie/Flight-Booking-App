<script setup lang="ts">
import {useRoute} from "vue-router";
import {computed} from "vue";

const route = useRoute();

const showRegister = computed(() => ['/sign-in', '/reset-password'].includes(route.path));
const showSignIn = computed(() => ['/sign-up'].includes(route.path));
</script>

<template>
    <nav class="absolute top-0 w-full">
        <div class="container mx-auto py-4 flex justify-between items-center">
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

    <slot />

    <div class="absolute bottom-0 w-full">
        <div class="container mx-auto py-4 flex justify-center items-center gap-x-6">
            <div class="text-slate-700">&copy; Miroslav Pašek 2024</div>
            <div class="text-slate-400">|</div>
            <div class="text-slate-700">All rights reserved</div>
        </div>
    </div>
</template>
