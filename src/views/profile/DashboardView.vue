<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import STPassportComponent from "@/components/admin/STPassportComponent.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faCircleArrowRight} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <admin-card class="col-span-12">
        <h1 class="text-center text-2xl font-medium mb-3">Welcome back Emily!</h1>
        <p class="text-center text-lg font-light">You have currently <span class="text-blue-600 font-medium">2</span> scheduled flights.</p>
    </admin-card>
    <admin-card :padding="false" class="col-span-4 h-64 overflow-hidden">
        <STPassportComponent />
    </admin-card>
    <admin-card :padding="false" class="col-span-4 h-64 overflow-hidden">
        <div class="h-full py-3 px-6 grid grid-rows-[1fr_1px_1fr] items-center gap-y-3">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="uppercase text-sm text-gray-700">Shortest flight distance</div>
                    <div class="font-semibold text-xl">623 km</div>
                </div>

                <div class="grid grid-cols-12 gap-x-3 w-full items-center">
                    <div class="col-span-2">
                        <img class="w-10 h-10 object-contain" src="@/static/companies/wizzair.png" alt="Wizzair" />
                    </div>
                    <div class="col-span-6">
                        <div class="flex gap-x-3 items-center -mb-1">
                            <div class="text-lg font-medium">CPH</div>
                            <font-awesome-icon :icon="faCircleArrowRight" />
                            <div class="text-lg font-medium">PRG</div>
                        </div>
                        <div class="font-light text-gray-700">Copenhagen to Prague</div>
                    </div>
                    <div class="col-span-4 text-right">
                        <div class="text-lg font-medium -mb-1">21. 11. 2021</div>
                        <div class="font-light text-gray-700">WZ 509</div>
                    </div>
                </div>
            </div>

            <div class="h-full bg-gray-200"></div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="uppercase text-sm text-gray-700">Longest flight distance</div>
                    <div class="font-semibold text-xl">1 750 km</div>
                </div>

                <div class="grid grid-cols-12 gap-x-3 w-full items-center">
                    <div class="col-span-2">
                        <img class="w-10 h-10 object-contain" src="@/static/companies/ryanair.png" alt="Ryanair" />
                    </div>
                    <div class="col-span-6">
                        <div class="flex gap-x-3 items-center -mb-1">
                            <div class="text-lg font-medium">PRG</div>
                            <font-awesome-icon :icon="faCircleArrowRight" />
                            <div class="text-lg font-medium">MAD</div>
                        </div>
                        <div class="font-light text-gray-700">Prague to Madrid</div>
                    </div>
                    <div class="col-span-4 text-right">
                        <div class="text-lg font-medium -mb-1">21. 11. 2021</div>
                        <div class="font-light text-gray-700">WZ 509</div>
                    </div>
                </div>
            </div>
        </div>
    </admin-card>
    <admin-card :padding="false" class="col-span-4 h-64 overflow-hidden">
        <div class="h-full py-3 px-6">
            <div class="mb-4">
                <div class="font-medium">Flight Distance</div>
                <div class="font-semibold text-3xl">
                    10 367 <span class="text-gray-700 font-normal">km</span>
                </div>
                <div class="text-gray-700 text-sm -mb-1">6 442 mi</div>
                <div class="text-sm">Average distance: 1 037 km</div>
            </div>

            <div class="space-y-2">
                <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="bg-purple-400 h-8" style="width: 33%"></div>

                    <img class="absolute top-0 h-full p-1 rounded-full" src="@/assets/images/space-elements/earth.svg" alt="Earth" />

                    <div class="absolute bottom-1/2 right-4 translate-y-1/2 text-sm">0,3x <span class="font-light">around Earth</span></div>
                </div>

                <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="bg-purple-400 h-8" style="width: 3%"></div>

                    <img class="absolute top-0 h-full p-1 rounded-full" src="@/assets/images/space-elements/moon.svg" alt="Moon" />

                    <div class="absolute bottom-1/2 right-4 translate-y-1/2 text-sm">0,03x <span class="font-light">to the Moon</span></div>
                </div>

                <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="bg-purple-400 h-8" style="width: 0%"></div>

                    <img class="absolute top-0 h-full p-1 rounded-full" src="@/assets/images/space-elements/mars.svg" alt="Mars" />

                    <div class="absolute bottom-1/2 right-4 translate-y-1/2 text-sm">0,00003x <span class="font-light">to Mars</span></div>
                </div>
            </div>
        </div>
    </admin-card>
</template>
