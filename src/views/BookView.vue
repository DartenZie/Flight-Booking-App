<script setup lang="ts">
import HeaderNav from '@/components/HeaderNav.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faShoePrints, faPlane } from '@fortawesome/free-solid-svg-icons';
</script>

<template>
    <header-nav />

    <div class="relative bg-copenhagen bg-cover bg-center h-160">
        <div class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-gradient-to-r from-green-100 via-green-50 to-transparent opacity-80"></div>

        <div
            class="container mx-auto h-full flex flex-auto items-center justify-start"
        >
            <p class="font-thin text-5xl leading-snug text-slate-950 z-10">
                Let's book your flight<br />
                to <span class="font-medium">Copenhagen</span>!
            </p>
        </div>
    </div>

    <div class="container mx-auto -mt-32">
        <div
            class="relative h-72 bg-white rounded-lg shadow-md px-10 pt-6 pb-12"
        >
            <h2 class="font-medium text-lg mb-8">
                <font-awesome-icon :icon="faPlane" />
                <span class="ms-3">Flight information</span>
            </h2>

            <div class="flex gap-x-4 mb-12">
                <div class="w-1/3 flex gap-x-4 items-center">
                    <div class="shrink-0">
                        <img
                            class="size-12 object-contain"
                            src="@/static/companies/wizzair.png"
                            alt="Wizz Air Logo"
                        />
                    </div>
                    <div class="text-xl font-medium text-black">
                        Wizz Air
                    </div>
                </div>

                <div class="w-1/3 flex gap-x-8 items-center ms-10">
                    <div class="w-12 flex flex-col items-end">
                        <div class="text-lg font-medium text-slate-950">12:10</div>
                        <div class="text-sm font-light text-slate-600">
                            PRG
                        </div>
                    </div>
                    <div class="border-b-2 border-dashed border-slate-950 w-28"></div>
                    <div class="w-16">
                        <div class="text-lg font-medium text-slate-950">13:30</div>
                        <div class="text-sm font-light text-slate-600">
                            CPH
                        </div>
                    </div>
                    <div class="text-lg font-normal text-slate-800">1h 20m</div>
                </div>

                <div class="w-1/3 flex items-center justify-end">
                    <div class="me-24">
                        <div class="text-right">
                            <font-awesome-icon :icon="faShoePrints" class="text-xs me-3" />
                            <span class="font-thin">Leg room: </span>
                            <span class="font-medium">74 cm</span>
                        </div>
                        <div class="text-right font-thin text-xs">
                            WizzAir &#183;  Airbus A321 Neo &#183; 5W3487
                        </div>
                    </div>
                    <div class="flex gap-x-1.5 items-center">
                        <div class="font-medium text-xl text-slate-950">39</div>
                        <div class="font-light text-sm text-slate-700">EUR</div>
                    </div>
                </div>
            </div>
            <div class="flex gap-x-4">
                <div class="w-1/3 flex gap-x-4 items-center">
                    <div class="shrink-0">
                        <img
                            class="size-12 object-contain"
                            src="@/static/companies/ryanair.png"
                            alt="RyanAir Logo"
                        />
                    </div>
                    <div class="text-xl font-medium text-black">
                        RyanAir
                    </div>
                </div>

                <div class="w-1/3 flex gap-x-8 items-center ms-10">
                    <div class="w-12 flex flex-col items-end">
                        <div class="text-lg font-medium text-slate-950">06:40</div>
                        <div class="text-sm font-light text-slate-600">
                            CPH
                        </div>
                    </div>
                    <div class="border-b-2 border-dashed border-slate-950 w-28"></div>
                    <div class="w-16">
                        <div class="text-lg font-medium text-slate-950">08:00</div>
                        <div class="text-sm font-light text-slate-600">
                            PRH
                        </div>
                    </div>
                    <div class="text-lg font-normal text-slate-800">1h 20m</div>
                </div>

                <div class="w-1/3 flex items-center justify-end">
                    <div class="me-24">
                        <div class="text-right">
                            <font-awesome-icon :icon="faShoePrints" class="text-xs me-3" />
                            <span class="font-thin">Leg room: </span>
                            <span class="font-medium">76 cm</span>
                        </div>
                        <div class="text-right font-thin text-xs">
                            RyanAir &#183;  Boeing 737 &#183; FR 7951
                        </div>
                    </div>
                    <div class="flex gap-x-1.5 items-center">
                        <div class="font-medium text-xl text-slate-950">49</div>
                        <div class="font-light text-sm text-slate-700">EUR</div>
                    </div>
                </div>
            </div>

            <router-link
                class="btn-primary absolute bottom-0 right-10 h-16 w-56 translate-y-1/2 text-left"
                to="/"
            >
                <span>Change Flights</span>
                <font-awesome-icon
                    :icon="faArrowLeft"
                    class="absolute right-6 bottom-1/2 translate-y-1/2"
                />
            </router-link>
        </div>
    </div>
</template>
