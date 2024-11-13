<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
</script>

<template>
    <admin-card class="col-span-12">
        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Schedule new flight</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Fill in the information about the flight.</p>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <h3 class="text-md font-medium text-gray-700 mb-6">Airplane type</h3>

                    <div class="mb-6">
                        <div class="w-1/2">
                            <label for="airplane" class="block text-sm font-medium mb-2">Airplane</label>
                            <select id="airplane" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <option selected value="" disabled>Select Airplane</option>
                                <option value="cz">Boeing 737-800</option>
                                <option value="ge">Boeing 737-8 MAX</option>
                                <option value="nor">Airbus A319</option>
                                <option value="fr">Airbus A320 Neo</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-x-10">
                        <div class="w-1/2">
                            <label for="departureAirport" class="block text-sm font-medium mb-2">Departure Airport</label>
                            <select id="departureAirport" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <option selected value="" disabled>Select departure airport</option>
                                <option value="cz">Vaclav Havel Airport Prague</option>
                                <option value="ge">Copenhagen Airport, Kastrup</option>
                                <option value="nor">Paris Charles de Gaulle Airport</option>
                                <option value="fr">Heathrow Airport</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label for="arrivalAirport" class="block text-sm font-medium mb-2">Arrival Airport</label>
                            <select id="arrivalAirport" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <option selected value="" disabled>Select arrival airport</option>
                                <option value="cz">Vaclav Havel Airport Prague</option>
                                <option value="ge">Copenhagen Airport, Kastrup</option>
                                <option value="nor">Paris Charles de Gaulle Airport</option>
                                <option value="fr">Heathrow Airport</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <h3 class="text-md font-medium text-gray-700 mb-6">Departure/Arrival time</h3>

                    <div class="flex items-center gap-x-10 mb-6">
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Departure Time</label>
                            <input type="time" id="departureTime" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                        </div>
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Arrival Time</label>
                            <input type="time" id="arrivalTime" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <h3 class="text-md font-medium text-gray-700">Boarding ticket prices</h3>
                    <p class="text-sm text-gray-700 mb-6">Average prices for flights on this route (excluding your airline’s flights).</p>

                    <div class="flex items-center gap-x-10 mb-6">
                        <div class="w-1/3">
                            <div class="text-lg -mb-1">Economy class</div>
                            <div class="text-sm text-gray-700">Avg. price: 84 EUR</div>
                        </div>
                        <div class="w-1/2">
                            <input type="number" id="economyClassPrice" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="100 EUR">
                        </div>
                    </div>

                    <div class="flex items-center gap-x-10 mb-6">
                        <div class="w-1/3">
                            <div class="text-lg -mb-1">Business class</div>
                            <div class="text-sm text-gray-700">Avg. price: 327 EUR</div>
                        </div>
                        <div class="w-1/2">
                            <input type="number" id="economyClassPrice" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="100 EUR">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-x-8">
                <router-link to="/admin/manage-flights" class="btn-text">Cancel</router-link>
                <button class="btn-primary h-10">
                    <span class="me-7">Schedule new flight</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </div>
    </admin-card>
</template>
