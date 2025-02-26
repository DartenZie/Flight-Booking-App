<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import {useAuthStore} from "@/store/auth.store";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faUser} from '@fortawesome/free-solid-svg-icons';

const isScrolled = ref(false);

const auth = useAuthStore();

const handleScroll = (): void => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(async () => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <nav :class="['fixed top-0 z-20 w-full transition-colors duration-200', isScrolled ? 'bg-white shadow-lg' : '']">
        <div class="container mx-auto p-4 md:px-8 xl:px-0 flex justify-between items-center">
            <router-link to="/" class="block">
                <span class="text-xl">
                    Sky<span class="font-semibold">Trip</span>
                </span>
            </router-link>

            <div v-if="auth.user">
                <router-link to="/profile/dashboard" class="btn-primary h-10 flex gap-x-4 items-center">
                    <font-awesome-icon :icon="faUser" />
                    {{ auth.user.firstName + ' ' + auth.user.lastName }}
                </router-link>
            </div>
            <div v-else class="flex gap-x-8">
                <router-link to="/sign-up" class="btn-text h-10 text-black hover:text-black">Register</router-link>
                <router-link to="/sign-in" class="btn-primary h-10">Sign In</router-link>
            </div>
        </div>
    </nav>

    <div class="min-h-screen relative overflow-x-hidden overflow-y-auto">
        <div class="mt-28 lg:mt-0">
            <slot />
        </div>

        <div class="h-20"></div>
        <div class="absolute bottom-0 w-full">
            <div class="container mx-auto p-4 md:px-8 xl:px-0 flex justify-center items-center gap-x-2 md:gap-x-4 lg:gap-x-6">
                <div class="text-slate-700">&copy; Miroslav Pašek 2024</div>
                <div class="text-slate-400">|</div>
                <div class="text-slate-700">All rights reserved</div>
            </div>
        </div>
    </div>
</template>
