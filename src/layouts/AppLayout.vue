<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const isScrolled = ref(false);

const handleScroll = (): void => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <nav :class="['fixed top-0 z-10 w-full transition-colors duration-200', isScrolled ? 'bg-white shadow-lg' : '']">
        <div class="container mx-auto py-4 flex justify-between items-center">
            <router-link to="/" class="block">
                <span class="text-xl">
                    Sky<span class="font-semibold">Trip</span>
                </span>
            </router-link>

            <div class="flex gap-x-8">
                <router-link to="/sign-up" class="btn-text h-10 text-black hover:text-black">Register</router-link>
                <router-link to="/sign-in" class="btn-primary h-10">Sign In</router-link>
            </div>
        </div>
    </nav>

    <slot />
</template>
