<script setup lang="ts">
defineProps<{
    city?: string;
    reservationSuccess?: boolean;
}>();
</script>

<template>
    <div class="mt-8 lg:mt-0 lg:bg-hero lg:bg-cover lg:bg-center lg:h-128 xl:h-160">
        <div
            class="container mx-auto px-4 md:px-8 lg:px-20 xl:px-0 h-full flex flex-auto items-center justify-start"
        >
            <p v-if="reservationSuccess && city" class="lg:-mt-20 xl:mt-0 font-thin text-3xl xl:text-5xl leading-snug text-gray-900">
                Reservation was successful! Enjoy<br />
                your trip to <span class="font-medium">{{ city }}</span>!
            </p>
            <p v-else-if="!city" class="lg:-mt-20 xl:mt-0 font-thin text-3xl xl:text-5xl leading-snug text-gray-900">
                Hey Buddy? where are you<br />
                <span class="font-medium">Flying</span> to?
            </p>
            <p v-else class="lg:-mt-20 xl:mt-0 font-thin text-3xl xl:text-5xl leading-snug text-gray-900">
                Let's book your flight<br />
                to <span class="font-medium">{{ city }}</span>!
            </p>
        </div>
    </div>

    <div class="lg:px-20 xl:px-0 container mt-8 lg:mt-0 mx-auto lg:-translate-y-1/2">
        <div class="relative px-4 md:px-8 bg-white lg:rounded-lg lg:shadow-md lg:px-10 lg:pt-6 lg:pb-12">
            <slot />
        </div>
    </div>
</template>
